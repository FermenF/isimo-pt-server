<?php

namespace App\Http\Controllers;

use App\Http\Traits\AuthTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    use AuthTrait;

    /**
     * User login with credentials and get AuthToken.
     */
    public function login(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                return $this->userLogin($request);
            });
        } catch (\Throwable $th) {
            return $this->sendResponse(code: 500, error: $th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function register(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                return $this->userRegister($request);
            });
        } catch (\Throwable $th) {
            return $this->sendResponse(code: 500, error: $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function profile()
    {
        try {
            return $this->userProfile();
        } catch (\Throwable $th) {
            return $this->sendResponse(code: 500, error: $th->getMessage());
        }
    }

    /**
     * Logout and remove the specified resource from storage.
     */
    public function logout()
    {
        try {
            return DB::transaction(function () {
                return $this->userLogout();
            });
        } catch (\Throwable $th) {
            return $this->sendResponse(code: 500, error: $th->getMessage());
        }
    }
}
