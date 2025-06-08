<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_can_access_client_dashboard()
    {
        $client = User::factory()->create(['role' => 'client']);

        $response = $this->actingAs($client)->get('/client/dashboard');

        $response->assertStatus(200);
    }

    public function test_client_cannot_access_restaurateur_dashboard()
    {
        $client = User::factory()->create(['role' => 'client']);

        $response = $this->actingAs($client)->get('/restaurateur/dashboard');
        $response->assertStatus(302);
        $response->assertRedirect('/client/dashboard');
    }

    public function test_restaurateur_can_access_restaurateur_dashboard()
    {
        $restaurateur = User::factory()->create(['role' => 'restaurateur']);

        $response = $this->actingAs($restaurateur)->get('/restaurateur/dashboard');

        $response->assertStatus(200);
    }

    public function test_restaurateur_cannot_access_client_dashboard()
    {
        $restaurateur = User::factory()->create(['role' => 'restaurateur']);

        $response = $this->actingAs($restaurateur)->get('/client/dashboard');
        $response->assertStatus(302);
        $response->assertRedirect('/restaurateur/dashboard');
    }

    public function test_admin_can_access_admin_dashboard()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
    }

    public function test_guest_is_redirected_to_login()
    {
        $response = $this->get('/client/dashboard');

        $response->assertRedirect('/login');
    }
}
