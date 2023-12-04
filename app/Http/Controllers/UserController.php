<?php

namespace App\Http\Controllers;

use App\Http\Traits\UserTrait;

class UserController extends Controller
{
    use UserTrait;

    public function index()
    {
        try {
            return $this->getAllUsers();
        } catch (\Throwable $th) {
            return $this->sendResponse(code: 500, error: $th->getMessage());
        }
    }
}
