<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return ApiResponse::sendResponse(401, 'Invalid credentials', null);
        }

        if ($user->role !== 'admin') {
            return ApiResponse::sendResponse(
                403,
                'Unauthorized',
                null
            );
        }

    // First login is valid, now ask for secret code
    return response()->json([
        'message' => 'Admin passcode required',
        'temp_token' => encrypt($user->id) // temporary encrypted user ID
    ]);

    }


    public function verify(Request $request)
    {
        $request->validate([
            'temp_token' => 'required',
            'admin_passcode' => 'required'
        ]);

    try {
        $userId = decrypt($request->temp_token);
        $user = User::findOrFail($userId);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Invalid token'], 400);
    }

    if ($user->role !== 'admin') {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    if ($request->admin_passcode !== env('ADMIN_SECRET_CODE')) {
        return response()->json(['message' => 'Invalid admin passcode'], 403);
    }

    // Generate API token (using Laravel Sanctum)
    $token = $user->createToken('admin-token')->plainTextToken;

    return response()->json([
        'message' => 'Admin authenticated',
        'token' => $token
    ]);
    }
}
