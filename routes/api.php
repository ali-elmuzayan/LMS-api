<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\FrontController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/', [FrontController::class , 'home'])
    ->name('home');




Route::controller(AuthController::class)
    ->group(function () {
        Route::post('/register', 'register')
            ->name('api.register');

        Route::post('/login', 'login')
            ->name('api.login');

        Route::post('/logout', 'logout')
            ->name('api.logout');

    });
