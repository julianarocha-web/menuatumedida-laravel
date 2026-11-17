<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guest_is_redirected_to_buyer_login(): void
    {
        $this->get(route('profile.edit'))->assertRedirect(route('buyer.login'));
        $this->patch(route('profile.update'), [])->assertRedirect(route('buyer.login'));
    }

    public function test_buyer_can_view_and_update_own_profile_only(): void
    {
        $buyer = User::factory()->create();
        $other = User::factory()->create();
        $data = ['name' => 'Nuevo nombre', 'email' => 'new@example.test', 'telefono' => '1122334455', 'modalidad_entrega' => 'delivery'];

        $this->actingAs($buyer)->get(route('profile.edit'))->assertOk()->assertSee($buyer->email);
        $this->patch(route('profile.update'), $data + ['id' => $other->id])->assertRedirect(route('profile.edit'));

        $this->assertDatabaseHas('users', ['id' => $buyer->id] + $data);
        $this->assertDatabaseHas('users', ['id' => $other->id, 'email' => $other->email]);
    }

    public function test_profile_rejects_admin_privileges(): void
    {
        $buyer = User::factory()->create();

        $this->actingAs($buyer)->patch(route('profile.update'), [
            'name' => $buyer->name, 'email' => $buyer->email, 'telefono' => '1122334455',
            'modalidad_entrega' => 'pickup', 'es_admin' => true,
        ])->assertSessionHasErrors('es_admin');

        $this->assertFalse($buyer->fresh()->es_admin);
        $this->get(route('admin.platos.index'))->assertForbidden();
    }

    public function test_profile_rejects_duplicate_email_and_invalid_contact_fields(): void
    {
        $buyer = User::factory()->create();
        $other = User::factory()->create();

        $this->actingAs($buyer)->patch(route('profile.update'), [
            'name' => '', 'email' => $other->email, 'telefono' => 'invalid', 'modalidad_entrega' => 'invalid',
        ])->assertSessionHasErrors(['name', 'email', 'telefono', 'modalidad_entrega']);

        $this->assertDatabaseHas('users', ['id' => $buyer->id, 'email' => $buyer->email]);
    }

    public function test_administrator_cannot_access_buyer_profile_or_logout(): void
    {
        $administrator = User::factory()->create();
        $administrator->forceFill(['es_admin' => true])->save();

        $this->actingAs($administrator)->get(route('profile.edit'))->assertForbidden();
        $this->patch(route('profile.update'), [])->assertForbidden();
        $this->post(route('buyer.logout'))->assertForbidden();

        $this->assertAuthenticatedAs($administrator);
    }

    public function test_profile_escapes_user_provided_name(): void
    {
        $buyer = User::factory()->create(['name' => '<script>alert(1)</script>']);

        $this->actingAs($buyer)->get(route('profile.edit'))->assertOk()
            ->assertSee($buyer->name)->assertDontSee($buyer->name, false);
    }
}
