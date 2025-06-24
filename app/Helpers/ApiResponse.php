<?php

namespace App\Helpers;


class ApiResponse
{

    public static function sendResponse($code = 200, $message = null, $data = null)
    {
        return response()->json([
            'status' => $code,
            'message' => $message ?? 'Operation successful',
            'data' => $data,
        ], $code);
    }


}
