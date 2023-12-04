<?php

namespace Tests\Unit\Post;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class PostUpdateTest extends TestCase
{
    /**
     * Post Update test
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

    private function register_previus_user_login_and_create_post()
    {
        $user = User::create([
            'name' => 'usuario de prueba',
            'email' => 'prueba@example.com',
            'password' => bcrypt('contraseña123'),
        ]);

        $post = Post::create([
            'user_id' => $user->id,
            'content' => 'Contenido de prueba',
            'url' => 'random-url',
        ]);

        return [
            'token' => $user->createToken('testToken')->plainTextToken,
            'post' => $post
        ];
    }

    public function test_post_has_ben_updated_successfully(): void
    {
        $result = $this->register_previus_user_login_and_create_post();
        $post = [
            'content' => 'Contenido de Prueba Actualizado',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $result['token'],
            'Accept'        => 'application/json',
        ])->put('/api/posts/' . $result['post']['id'], $post);

        $post['user_id'] = Auth::id();
        $this->assertDatabaseHas('posts', $post);
        $response->assertStatus(200);
    }

    public function test_post_cannot_be_updated_by_different_user(): void
    {
        $result = $this->register_previus_user_login_and_create_post();
        $post = [
            'content' => 'Contenido de Prueba Actualizado',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $result['token'],
            'Accept'        => 'application/json',
        ])->put('/api/posts/' . $result['post']['id'] + 1, $post);

        $post['user_id'] = Auth::id();
        $this->assertDatabaseMissing('posts', $post);
        $response->assertStatus(404);
    }

    public function test_post_required_fields_validation(): void
    {
        $result = $this->register_previus_user_login_and_create_post();
        $post = [
            'content' => '',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $result['token'],
            'Accept' => 'application/json',
        ])->post('/api/posts', $post);

        $post['user_id'] = Auth::id();

        $this->assertDatabaseMissing('posts', $post);
        $response->assertStatus(422);
    }
}
