<?php

namespace App\Http\Controllers;

use App\Http\Traits\UserTrait;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use UserTrait;

    public function index(Request $request)
    {
        try {
            return $this->getAllUsers($request);
        } catch (\Throwable $th) {
            return $this->sendResponse(code: 500, error: $th->getMessage());
        }
    }

    public function show($id)
    {
        try {
            return $this->getUserById($id);
        } catch (\Throwable $th) {
            return $this->sendResponse(code: 500, error: $th->getMessage());
        }
    }
}
