<?php

namespace Tests\Unit\Post;

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class PostGetAllTest extends TestCase
{
    /**
     * Get Lists of posts test
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

    public function test_get_all_posts_with_user_authenticated(): void
    {
        $token = $this->register_previus_user_login();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->get('/api/posts');

        $response->assertStatus(200);
    }

    public function test_dont_get_all_posts_with_user_unauthenticated(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . 'this-is-a-invalid-t0ken',
            'Accept' => 'application/json',
        ])->get('/api/posts');

        $response->assertStatus(401);
        $response->assertJson(['message' => 'Unauthenticated.']); 
    }
}
