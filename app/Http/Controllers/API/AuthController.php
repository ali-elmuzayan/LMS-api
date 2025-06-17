<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login (LoginRequest $request) {
        $user = User::where('email',  $request->email)->first();

        if(!$user)  {
            throw ValidationException::withMessages(['email' => ['The provided credentials are incorrect.']]);
        }

        // check email
        if(Hash::check($user->password, $request->password)) {
            return $user;
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
        ]);
    }
}
