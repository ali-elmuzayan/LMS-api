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


Route::post('/register', [AuthController::class, 'register'])
    ->name('api.register');
