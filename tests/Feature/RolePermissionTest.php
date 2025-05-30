<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RolePermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_admin_route()
    {
        $adminRole = Role::create(['name' => 'Admin']);

        $user = User::factory()->create();
        $user->roles()->attach($adminRole->id);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/admin');

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Welcome, Admin!',
        ]);
    }

    public function test_non_admin_cannot_access_admin_route()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/admin');

        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'Forbidden',
        ]);
    }

    public function test_admin_can_create_role()
    {
        $adminRole = Role::create(['name' => 'Admin']);

        $user = User::factory()->create();
        $user->roles()->attach($adminRole->id);

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/roles', [
            'name' => 'Editor',
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => ['id', 'name'],
        ]);
        $response->assertJsonFragment(['name' => 'Editor']);
    }

    public function test_non_admin_cannot_create_role()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/roles', [
            'name' => 'Editor',
        ]);

        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'Forbidden',
        ]);
    }
}
