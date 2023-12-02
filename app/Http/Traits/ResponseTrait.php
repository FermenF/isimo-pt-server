<?php

namespace App\Http\Traits;

trait ResponseTrait
{
    function sendResponse($code = 200, $data = [], $message = "", $error = "")
    {
        $response = [
            'data' => $data,
            'message' => $message,
            'error' => $error,
        ];
    
        return response()->json($response, $code);
    }
}
