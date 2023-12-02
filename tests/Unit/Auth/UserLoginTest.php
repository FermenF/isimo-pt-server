<?php

namespace Tests\Unit\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class UserLoginTest extends TestCase
{
    /**
     * User Login test
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
        User::insert([
            'name' => 'usuario de prueba',
            'email' => 'prueba@example.com',
            'password' => bcrypt('contraseña123'),
        ]);
    }

    public function test_user_can_login_successfully(): void
    {
        $this->register_previus_user_login();

        $response = $this->postJson('/api/auth/login', [
            'email' => 'prueba@example.com',
            'password' => 'contraseña123',
        ]);

        $response->assertStatus(200);
    }

    public function test_user_have_invalid_credential_for_login_successfully(): void
    {
        $this->register_previus_user_login();

        $response = $this->postJson('/api/auth/login', [
            'email' => 'prueba@example.com',
            'password' => 'contraseña1235',
        ]);

        $response->assertStatus(401);
    }

    public function test_required_fields_validation(): void
    {
        $this->register_previus_user_login();

        $response = $this->postJson('/api/auth/login', [
            'email' => 'prueba@example.com',
            'password' => '2',
        ]);

        $response->assertStatus(422);
    }
}
