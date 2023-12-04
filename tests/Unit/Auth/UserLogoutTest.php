<?php

namespace Tests\Unit\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class UserLogoutTest extends TestCase
{
    /**
     * User Logout test
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

    public function test_user_authenticated_can_loguot(): void
    {
        $token = $this->register_previus_user_login();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->post('/api/auth/logout');

        $response->assertStatus(200);
    }

    public function test_user_unauthenticated_cannot_logout(): void
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/auth/logout');

        $response->assertStatus(401);
        $response->assertJson(['message' => 'Unauthenticated.']);
    }

    public function test_user_unauthenticated_with_invalid_token_cannot_logout(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . 'this-is-a-invalid-t0ken',
            'Accept' => 'application/json',
        ])->post('/api/auth/logout');

        $response->assertStatus(401);
        $response->assertJson(['message' => 'Unauthenticated.']);
    }
}
