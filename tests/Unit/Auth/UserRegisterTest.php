<?php

namespace Tests\Unit\Auth;

use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class UserRegisterTest extends TestCase
{
    /**
     * User Register test
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

    public function test_user_can_register_successfully(): void
    {
        $user = [
            'name' => 'Usuario de Prueba',
            'email' => 'prueba@example.com',
            'password' => 'contraseña123',
            'password_confirmation' => 'contraseña123',
        ];

        $response = $this->postJson('/api/auth/register', $user);

        $user['name'] = strtolower($user['name']);
        $user['email'] = strtolower($user['email']);

        unset($user['password_confirmation']);
        unset($user['password']);

        $this->assertDatabaseHas('users', $user);
        $response->assertStatus(201);
    }

    public function test_duplicate_email_results_in_validation_error(): void
    {
        $this->test_user_can_register_successfully(); 

        $response = $this->postJson('/api/auth/register', [
            'name' => 'Usuario de Prueba',
            'email' => 'prueba@example.com',
            'password' => 'contraseña123',
            'password_confirmation' => 'contraseña123',
        ]);

        $response->assertStatus(422);
    }

    public function test_password_confirmation_must_match_password(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Usuario de Prueba',
            'email' => 'prueba2@example.com',
            'password' => 'contraseña123',
            'password_confirmation' => 'contraseña1234',
        ]);

        $response->assertStatus(422);
    }

    public function test_required_fields_validation(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => '',
            'email' => 'invalid-email',
            'password' => 'contraseña123',
            'password_confirmation' => 'contraseña123',
        ]);

        $response->assertStatus(422);
    }
}
