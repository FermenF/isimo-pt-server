<?php

namespace App\Http\Traits;

use App\Models\Post;
use App\Models\PostImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

trait PostTrait
{
    use ResponseTrait;

    public function getAllPosts()
    {
        try {
            $posts = Post::with(['user:id,name,email,photo', 'postImages:id,post_id,path,url'])->latest()->paginate(15);
            
            return $this->sendResponse(data: $posts, message: "Listado de publicaciones");
        } catch (\Throwable $th) {
            return $this->sendResponse(code: 500, message: "Error al obtener listado de publicaciones", error: $th->getMessage());
        }
    }

    public function storePost($request)
    {
        $validator = $this->validatePostContent($request);

        if ($validator->fails()) {
            return $this->sendResponse(422, message: $validator->errors()->first());
        };

        try {
            $post = Post::create([
                'user_id' => Auth::id(),
                'content' => $request->content,
                'url' => Uuid::uuid4()->toString() . '-' . Auth::id(),
            ]);

            if ($request->hasFile('images')) {
                $images = $this->savePostImages($request, $post->id);
                $post['post_images'] = $images;
            }

            return $this->sendResponse(code: 201, data: $post, message: "Publicación creada correctamente",);
        } catch (\Throwable $th) {
            return $this->sendResponse(code: 500, message: "Error creando la publicación", error: $th->getMessage());
        }
    }

    public function getPostById($id)
    {
        try {
            $post = Post::find($id);

            if ($post) {
                return $this->sendResponse(data: $post, message: "Información del publicación");
            }
            return $this->sendResponse(code: 404, message: "Publicación no encontrada.");
        } catch (\Throwable $th) {
            return $this->sendResponse(code: 500, message: "Error al obtener publicación", error: $th->getMessage());
        }
    }

    public function updatePostById($id, $request)
    {
        $validator = $this->validatePostContent($request);

        if ($validator->fails()) {
            return $this->sendResponse(422, message: $validator->errors()->first());
        };

        try {
            $post = Post::find($id);
            if ($post && $post->user_id === Auth::id()) {
                $post->update(['content' => $request->content]);
                return $this->sendResponse(message: "Publicación actualizada correctamente",);
            };
            return $this->sendResponse(404, message: "Publicación no encontrada");
        } catch (\Throwable $th) {
            return $this->sendResponse(code: 500, message: "Error actualizando la publicación", error: $th->getMessage());
        }
    }

    public function destroyPostById($id)
    {
        try {
            $post = Post::find($id);
            if ($post && $post->user_id === Auth::id()) {
                $post->delete();
                return $this->sendResponse(message: "Publicación eliminada correctamente");
            };
            return $this->sendResponse(code: 404, message: "Publicación no encontrada");
        } catch (\Throwable $th) {
            return $this->sendResponse(code: 500, error: $th->getMessage());
        }
    }

    private function validatePostContent($request)
    {
        $rules = [
            'content' => 'nullable|string',
        ];
    
        if ($request->hasFile('images')) {
            $rules['images.*'] = 'image|mimes:jpeg,png,jpg,gif';
        } else {
            $rules['content'] = 'required|string';
        }
    
        return Validator::make($request->all(), $rules);
    }

    private function savePostImages($request, $post_id)
    {
        try {
            $postImages = [];

            foreach ($request->file('images') as $index => $imageFile) {
                $originalExtension = $imageFile->getClientOriginalExtension();
                $filename = Uuid::uuid4()->toString() . '-' . Auth::id() . '-' . $index . '.' . $originalExtension;
                $path = 'images/' . Auth::id() . '/posts/post/' . $post_id;
                $imageFile->storeAs($path, $filename, 'public');

                $postImages[] = [
                    'post_id' => $post_id,
                    'url' => $filename,
                    'path' => $path,
                ];
            }

            PostImage::insert($postImages);

            return $postImages;

        } catch (\Throwable $th) {
            throw "Error al subir imagenes";
        }
    }
}
