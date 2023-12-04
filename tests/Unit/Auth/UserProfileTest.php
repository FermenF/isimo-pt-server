<?php

namespace Tests\Unit\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    /**
     * User Profile test
     */

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call("migrate");
    }

    public function tearDown(): void
    {
        Artisan::call('migrate:rollback');
        parent::tearDown();
    }

    private function register_previus_user_login()
    {
        $user = User::create([
            'name' => 'usuario de prueba',
            'email' => 'prueba@example.com',
            'password' => bcrypt('contraseña123'),
        ]);

        return $user->createToken('testToken')->plainTextToken;
    }

    public function test_user_authenticated_can_get_profile(): void
    {
        $token = $this->register_previus_user_login();
        $response = $this->get('/api/auth/profile', [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                "id"    => $response['data']['id'],
                "name"  => $response['data']['name'],
                "email" => $response['data']['email']
            ],
        ]);
    }

    public function test_user_unauthenticated_cannot_get_profile(): void
    {
        $response = $this->get('/api/auth/profile', [
            'Accept' => 'application/json',
        ]);
        $response->assertStatus(401);
        $response->assertJson(['message' => 'Unauthenticated.']);
    }

    public function test_user_unauthenticated_with_invalid_token_cannot_get_profile(): void
    {
        $response = $this->get('/api/auth/profile', [
            'Authorization' => 'Bearer ' . 'this-is-a-invalid-t0ken',
            'Accept' => 'application/json',
        ]);
        $response->assertStatus(401);
        $response->assertJson(['message' => 'Unauthenticated.']);
    }
}
