<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

class BuyerSessionTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_registration_creates_and_authenticates_a_buyer(): void
    {
        $this->post(route('buyer.register'), $this->registration())
            ->assertRedirect(route('profile.edit'));

        $buyer = User::where('email', 'buyer@example.test')->firstOrFail();
        $this->assertAuthenticatedAs($buyer);
        $this->assertFalse($buyer->es_admin);
        $this->assertTrue(Hash::check('BuyerPass123', $buyer->password));
        $this->assertDatabaseHas('users', ['id' => $buyer->id, 'telefono' => '+54 11 12345678', 'modalidad_entrega' => 'pickup']);
    }

    #[TestWith(['password', 'weak'])]
    #[TestWith(['password_confirmation', 'different'])]
    #[TestWith(['modalidad_entrega', 'invalid'])]
    #[TestWith(['telefono', 'letters'])]
    #[TestWith(['terms', '0'])]
    #[TestWith(['es_admin', true])]
    public function test_registration_rejects_invalid_data_and_privilege_escalation(string $field, string|bool $value): void
    {
        $this->post(route('buyer.register'), array_replace($this->registration(), [$field => $value]))
            ->assertSessionHasErrors($field === 'password_confirmation' ? 'password' : $field);

        $this->assertDatabaseCount('users', 0);
        $this->assertGuest();
    }

    public function test_registration_rejects_duplicate_email(): void
    {
        User::factory()->create(['email' => 'buyer@example.test']);

        $this->post(route('buyer.register'), $this->registration())->assertSessionHasErrors('email');

        $this->assertDatabaseCount('users', 1);
        $this->assertGuest();
    }

    public function test_buyer_can_log_in_and_log_out(): void
    {
        $buyer = User::factory()->create(['email' => 'buyer@example.test']);

        $this->post(route('buyer.login.store'), ['email' => $buyer->email, 'password' => 'password'])
            ->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($buyer);
        $this->post(route('buyer.logout'))->assertRedirect(route('home'));
        $this->assertGuest();
        $this->get(route('profile.edit'))->assertRedirect(route('buyer.login'));
    }

    public function test_wrong_password_does_not_log_in(): void
    {
        $buyer = User::factory()->create();

        $this->post(route('buyer.login.store'), ['email' => $buyer->email, 'password' => 'incorrect'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_administrator_cannot_log_in_through_buyer_login(): void
    {
        $administrator = User::factory()->create();
        $administrator->forceFill(['es_admin' => true])->save();

        $this->post(route('buyer.login.store'), ['email' => $administrator->email, 'password' => 'password'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_buyer_login_is_rate_limited(): void
    {
        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post(route('buyer.login.store'), ['email' => 'unknown@example.test', 'password' => 'incorrect']);
        }

        $this->post(route('buyer.login.store'), ['email' => 'unknown@example.test', 'password' => 'incorrect'])
            ->assertTooManyRequests();
    }

    /** @return array<string, string> */
    private function registration(): array
    {
        return [
            'name' => 'Compradora', 'email' => 'buyer@example.test', 'telefono' => '+54 11 12345678',
            'modalidad_entrega' => 'pickup', 'password' => 'BuyerPass123',
            'password_confirmation' => 'BuyerPass123', 'terms' => '1',
        ];
    }
}
