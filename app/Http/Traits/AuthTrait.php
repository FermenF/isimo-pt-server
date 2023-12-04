<?php

namespace App\Http\Traits;

use App\Mail\SendEmailToRegisteredUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

trait AuthTrait
{
    use ResponseTrait;

    public function userLogin($request)
    {
        $validator = $this->validateUserLogin($request);

        if ($validator->fails()) {
            return $this->sendResponse(422, message: $validator->errors()->first());
        };

        try {
            $user = User::where('email', $request->email)->first();
            if (!$user || !Hash::check($request->password, $user->password)) {
                return $this->sendResponse(401, message: 'Crendenciales Invalidas, intenta nuevamente.');
            };
            $token = $user->createToken('accessToken')->plainTextToken;
            return $this->sendResponse(data: ['token' => $token], message: '¡Inicio de sesión exitoso!');

        } catch (\Throwable $th) {
            return $this->sendResponse(500, message: "¡Error al iniciar sesión!", error: $th->getMessage());
        }
    }

    public function userRegister($request)
    {
        $validator = $this->validateUserRegistration($request);

        if ($validator->fails()) {
            return $this->sendResponse(422, message: $validator->errors()->first());
        };

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);

            $this->sendEmail($request->email, $user);

            return $this->sendResponse(201, message: "¡Registro Exitoso!");
        } catch (\Throwable $th) {
            return $this->sendResponse(500, message: "¡Error al registrar!", error: $th->getMessage());
        }
    }

    public function userLogout()
    {
        try {
            $user = Auth::user();
            $token_id = $user->currentAccessToken()->id;
            $user->tokens()->where('id', $token_id)->delete();
            return $this->sendResponse(200, message: "Se ha cerrado la sesión correctamente");
        } catch (\Throwable $th) {
            return $this->sendResponse(500, message: "¡Error al cerrar sesión!", error: $th->getMessage());
        }
    }

    public function userProfile()
    {
        try {
            $user = Auth::user();
            return $this->sendResponse(data: $user, message: "Perfil de usuario");
        } catch (\Throwable $th) {
            return $this->sendResponse(500, message: "¡Error al obtener perfil de usuario!", error: $th->getMessage());
        }
    }

    private function sendEmail($email, $user)
    {
        try {
            Mail::to($email)->send(new SendEmailToRegisteredUser($user));
        } catch (\Throwable $th) {
            Log::error('Error al enviar el correo electrónico: ' . $th->getMessage());
            throw $th->getMessage();
        }
    }

    private function validateUserLogin($request)
    {
        return Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
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
