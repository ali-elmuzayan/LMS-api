<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


//Home



// user frontend
Route::get('/dashboard', function () {
    return response()->json(['message' => 'API worked!'], 200);
});
