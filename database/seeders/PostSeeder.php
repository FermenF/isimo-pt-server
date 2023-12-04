<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'user_id' => 1,
                'content' => 'Esto es un posts cargado dede PostSeeder',
                'url' => Uuid::uuid4()->toString(),
            ],
            [
                'user_id' => 2,
                'content' => 'Esto es otro posts cargado dede PostSeeder con otro usuario',
                'url' => Uuid::uuid4()->toString(),
            ]
        ];
        Post::insert($data);
    }
}
