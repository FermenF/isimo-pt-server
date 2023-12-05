<?php

namespace App\Http\Traits;

use App\Models\User;

trait UserTrait
{
    use ResponseTrait;

    public function getAllUsers($request)
    {
        try {
            $users = User::byEmail($request->email)->latest()->paginate(15);
            return $this->sendResponse(data: $users, message: "Listado de usuarios");
        } catch (\Throwable $th) {
            return $this->sendResponse(code: 500, message: "Error al obtener listado de usuarios", error: $th->getMessage());
        }
    }

    public function getUserById($id)
    {
        try {
            $user = User::with('posts.postImages')->find($id)->makeVisible(['created_at']);
            if ($user) {
                return $this->sendResponse(data: $user, message: "Informacion del usuario");
            }
            return $this->sendResponse(code: 404, message: "Usuario no encontrado");
        } catch (\Throwable $th) {
            return $this->sendResponse(code: 500, message: "Error al obtener información del usuario", error: $th->getMessage());
        }
    }
}
