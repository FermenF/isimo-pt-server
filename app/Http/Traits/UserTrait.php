<?php

namespace App\Http\Traits;

use App\Models\User;

trait UserTrait
{
    use ResponseTrait;

    public function getAllUsers()
    {
        try {
            $users = User::latest()->paginate(15)->makeVisible(['created_at']);;
            return $this->sendResponse(data: $users, message: "Listado de usuarios");
        } catch (\Throwable $th) {
            return $this->sendResponse(code: 500, message: "Error al obtener listado de usuarios", error: $th->getMessage());
        }
    }
}
