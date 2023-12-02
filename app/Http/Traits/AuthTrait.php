<?php

namespace App\Http\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Validator;

trait AuthTrait
{
    use ResponseTrait;

    public function userLogin()
    {
        try {
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function userRegister($request)
    {
        $validator = $this->validateUserRegistration($request);

        if ($validator->fails()) {
            return $this->sendResponse(422, message: $validator->errors()->first());
        }

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);

            return $this->sendResponse(message: "¡Registro Exitoso!");
        } catch (\Throwable $th) {
            return $this->sendResponse(500, message: "¡Error al registrar!", error: $th->getMessage());
        }
    }

    public function userLogout()
    {
    }

    private function validateUserRegistration($request)
    {
        return Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|string|min:6|same:password',
        ]);
    }
}
