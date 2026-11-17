<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guest_is_redirected_to_admin_login(): void
    {
        $this->get('/admin/platos')
            ->assertRedirect('/admin/login');
    }

    public function test_authenticated_buyer_is_forbidden_from_admin_catalog(): void
    {
        $buyer = User::factory()->create();

        $this->actingAs($buyer)
            ->get('/admin/platos')
            ->assertForbidden();
    }

    public function test_administrator_can_access_admin_catalog(): void
    {
        $administrator = User::factory()->create();
        $administrator->es_admin = true;
        $administrator->save();

        $this->actingAs($administrator)
            ->get('/admin/platos')
            ->assertOk();
    }
}
