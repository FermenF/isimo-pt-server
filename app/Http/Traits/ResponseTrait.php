<?php

namespace App\Http\Traits;

trait ResponseTrait
{
    function sendResponse($code = 200, $data = [], $message = "Internal Server Error", $error = "")
    {
        $response = [
            'data' => $data,
            'message' => $message,
            'error' => $error,
        ];
    
        return response()->json($response, $code);
    }
}
