<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class AdminSessionTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_administrator_can_log_in_and_log_out(): void
    {
        $administrator = User::factory()->create(['email' => 'admin@example.test']);
        $administrator->es_admin = true;
        $administrator->save();

        $this->post(route('admin.login.store'), [
            'email' => 'admin@example.test',
            'password' => 'password',
        ])->assertRedirect(route('admin.platos.index'));

        $this->assertAuthenticatedAs($administrator);

        $this->post(route('admin.logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_buyer_cannot_log_in_to_administration(): void
    {
        User::factory()->create(['email' => 'buyer@example.test']);

        $this->from(route('login'))->post(route('admin.login.store'), [
            'email' => 'buyer@example.test',
            'password' => 'password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }
}
