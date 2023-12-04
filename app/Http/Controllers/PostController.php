<?php

namespace App\Http\Controllers;

use App\Http\Traits\PostTrait;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    use PostTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return $this->getAllPosts();
        } catch (\Throwable $th) {
            return $this->sendResponse(code: 500, error: $th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                return $this->storePost($request);
            });
        } catch (\Throwable $th) {
            return $this->sendResponse(code: 500, error: $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            return $this->getPostById($id);
        } catch (\Throwable $th) {
            return $this->sendResponse(code: 500, error: $th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                return $this->updatePostById($id, $request);
            });
        } catch (\Throwable $th) {
            return $this->sendResponse(code: 500, error: $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            return DB::transaction(function () use ($id) {
                return $this->destroyPostById($id);
            });
        } catch (\Throwable $th) {
            return $this->sendResponse(code:500, error: $th->getMessage());
        }
    }
}
