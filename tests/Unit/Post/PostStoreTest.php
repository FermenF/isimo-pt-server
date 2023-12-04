<?php

namespace Tests\Unit\Post;

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class PostStoreTest extends TestCase
{
    /**
     * Post Store test
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

    public function test_post_has_ben_stored_successfully(): void
    {
        $token = $this->register_previus_user_login();
        $post = [
            'content' => 'Contenido de Prueba',
        ];
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->post('/api/posts', $post);

        $post['user_id'] = Auth::id();
        $this->assertDatabaseHas('posts', $post);
        $response->assertStatus(201);
    }

    public function test_post_required_fields_validation(): void
    {
        $token = $this->register_previus_user_login();
        $post = [
            'content' => '',
        ];
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->post('/api/posts', $post);

        $post['user_id'] = Auth::id();
        $this->assertDatabaseMissing('posts', $post);
        $response->assertStatus(422);
    }
    
}
