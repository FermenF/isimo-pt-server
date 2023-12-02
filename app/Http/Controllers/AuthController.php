<?php

namespace App\Http\Controllers;

use App\Http\Traits\AuthTrait;
use App\Http\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    use AuthTrait, ResponseTrait;

    public function login(Request $request)
    {
        
    }

    public function register(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                return $this->userRegister($request);
            });
        } catch (\Throwable $th) {
            return $this->sendResponse(code:500, message: "Internal Server Error", error: $th->getMessage());
        }
    }
}
