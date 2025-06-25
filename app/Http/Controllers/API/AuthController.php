<?php

namespace App\Http\Controllers\API;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Notifications\NewInstructorRegistered;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use LDAP\Result;

class AuthController extends Controller implements HasMiddleware
{

    public static function middleware() {
        return [
            new Middleware('auth:sanctum', only: ['logout']),
        ];
    }


    public function register(Request $request) {
        // $data = $request->validated();
        $data = $request->all();



        // create the user:
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'] ?? 'student', // default role is 'user'
            'is_approved' => $data['role'] === 'student', // Auto-approve students
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        // if ($user->role === 'instructor') {
        //     User::admin()->first()->notify(new NewInstructorRegistered($user));
        // }


        // handle the response message:

        $message =  $user->role === 'student'
            ? 'Registration successful!'
            : 'Registration pending admin approval.';


        return ApiResponse::sendResponse(201, $message, [
            'user' => new UserResource($user),
            'access_token' => $token,
        ]);

    }


    // public function login (LoginRequest $request) {
    //     $user = User::where('email',  $request->email)->first();

    //     if(!$user)  {
    //         throw ValidationException::withMessages(['email' => ['The provided credentials are incorrect.']]);
    //     }

    //     // check email
    //     if(Hash::check($user->password, $request->password)) {
    //         return $user;
    //     }

    //     $token = $user->createToken('api-token')->plainTextToken;

    //     return response()->json([
    //         'access_token' => $token,
    //     ]);
    // }


    public function Login(LoginRequest $request) {


        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages(['email' => ['The provided credentials are incorrect.']]);
        }



        $user = Auth::user();

        // if (!$user->is_approved) {
        //     throw ValidationException::withMessages(['email' => ['Your account is not approved yet.']]);
        // }

        $token = $user->createToken('auth_token')->plainTextToken;

        Log::info('User logged in', ['user_id' => $user->id]);

        return ApiResponse::sendResponse(200, 'Login successful.', [
            'user' => new UserResource($user),
            'access_token' => $token,
        ]);

    }


    public function logout(Request $request) {
        $user = $request->user();
        $user->currentAccessToken()->delete();

        Log::info('User logged out', ['user_id' => $request->user()->id]);

        return ApiResponse::sendResponse(200, 'Logged out successfully.', null);
    }
}
