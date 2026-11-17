<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Plato;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\TestWith;
use Symfony\Component\Process\Process;
use Tests\TestCase;

class MySqlCheckoutConcurrencyTest extends TestCase
{
    #[TestWith([false])]
    #[TestWith([true])]
    public function test_simultaneous_confirmations_do_not_oversell_or_duplicate(bool $sameBuyer): void
    {
        if (DB::getDriverName() !== 'mysql' || ! str_starts_with(DB::connection()->getDatabaseName(), 'stage5_test_')) {
            $this->markTestSkipped('Requires an isolated stage5_test_ MySQL database.');
        }

        $plato = Plato::factory()->create(['stock' => 2]);
        $first = User::factory()->create();
        $second = $sameBuyer ? $first : User::factory()->create();
        $firstToken = (string) Str::uuid();
        $secondToken = $sameBuyer ? $firstToken : (string) Str::uuid();
        $before = Order::count();
        $processes = [
            $this->confirmation($first, $plato, $firstToken),
            $this->confirmation($second, $plato, $secondToken),
        ];

        DB::beginTransaction();
        try {
            Plato::whereKey($plato->id)->lockForUpdate()->firstOrFail();
            foreach ($processes as $process) {
                $process->start();
            }
        } finally {
            DB::commit();
        }
        try {
            $codes = [];
            foreach ($processes as $process) {
                $codes[] = $process->wait();
                $this->assertContains($process->getExitCode(), [0, 2], $process->getErrorOutput().$process->getOutput());
            }
            sort($codes);
            $this->assertSame($sameBuyer ? [0, 0] : [0, 2], $codes);
            $this->assertSame($before + 1, Order::count());
            $this->assertSame(0, $plato->availableStock());
            $this->assertSame(2, $plato->fresh()->stock);
            $this->assertSame(1, $plato->orderItems()->count());
        } finally {
            foreach ($processes as $process) {
                $process->stop();
            }
            Order::whereIn('checkout_token', [$firstToken, $secondToken])->each(function (Order $order): void {
                $order->items()->delete();
                $order->delete();
            });
            $plato->delete();
            $plato->categoria()->delete();
            User::whereIn('id', [$first->id, $second->id])->delete();
        }
    }

    private function confirmation(User $buyer, Plato $plato, string $token): Process
    {
        $config = DB::connection()->getConfig();
        $payload = json_encode([
            'buyer' => $buyer->id, 'plato' => $plato->id, 'token' => $token,
        ], JSON_THROW_ON_ERROR);
        $code = <<<'PHP'
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$data = json_decode(getenv('CHECKOUT_TEST_PAYLOAD'), true);
$buyer = App\Models\User::findOrFail($data['buyer']);
$request = Illuminate\Http\Request::create('/checkout', 'POST', ['checkout_token' => $data['token']]);
$session = $app['session']->driver();
$session->start();
$session->put('cart', [$data['plato'] => 2]);
$session->put('checkout', [
    'token' => $data['token'], 'user_id' => $buyer->id,
    'snapshot' => [$data['plato'] => ['quantity' => 2, 'unit_cents' => 100525]],
]);
$request->setLaravelSession($session);
$request->setUserResolver(fn () => $buyer);
echo "READY\n";
flush();
try {
    $app->make(App\Http\Controllers\CheckoutController::class)->store($request);
    echo "CONFIRMED\n";
} catch (Illuminate\Validation\ValidationException $exception) {
    echo "UNAVAILABLE\n";
    exit(2);
}
PHP;

        return new Process([PHP_BINARY], base_path(), [
            'APP_ENV' => 'testing', 'DB_CONNECTION' => 'mysql', 'DB_URL' => '',
            'DB_HOST' => $config['host'], 'DB_PORT' => (string) $config['port'],
            'DB_DATABASE' => $config['database'], 'DB_USERNAME' => $config['username'],
            'DB_PASSWORD' => $config['password'], 'SESSION_DRIVER' => 'array',
            'CHECKOUT_TEST_PAYLOAD' => $payload,
        ], "<?php\n".$code, 15);
    }
}
