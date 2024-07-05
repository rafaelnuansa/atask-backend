<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [App\Http\Controllers\Api\RegisterController::class, 'index']);
Route::post('/login', [App\Http\Controllers\Api\LoginController::class, 'index']);

Route::group(['middleware' => 'auth:api'], function() {
    Route::post('/logout', [App\Http\Controllers\Api\LoginController::class, 'logout']);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/tasks', [App\Http\Controllers\Api\TaskController::class, 'index']);
Route::post('/tasks', [App\Http\Controllers\Api\TaskController::class, 'store']);
Route::get('/tasks/{id}', [App\Http\Controllers\Api\TaskController::class, 'show']);
Route::put('/tasks/{id}', [App\Http\Controllers\Api\TaskController::class, 'update']);
Route::delete('/tasks/{id}', [App\Http\Controllers\Api\TaskController::class, 'destroy']);