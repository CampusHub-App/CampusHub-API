<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\EventController;

Route::group(['prefix' => 'auth'], function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login/user', [AuthController::class, 'userlogin']);
    Route::post('/login/admin', [AuthController::class, 'adminlogin']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
});

Route::group(['prefix' => 'user', 'middleware' => 'auth:api'], function () {
    Route::get('/', [UserController::class, 'read']);
    Route::post('/', [UserController::class, 'update']);
    Route::patch('/change-password', [UserController::class, 'change']);
    Route::delete('/', [UserController::class, 'delete']);
});

Route::group(['prefix' => 'events'], function () {
    Route::get('/', [EventController::class, 'index']);
    Route::get('/{id}', [EventController::class, 'details']);
});

Route::group(['prefix' => 'events', 'middleware' => 'auth:api'], function () {
    Route::post('/{id}/register', [EventController::class, 'register']);
    Route::post('/', action: [EventController::class, 'create']);
    Route::post('/{id}', [EventController::class, 'update']);
    Route::delete('/{id}', [EventController::class, 'delete']);
});

Route::group(['prefix' => 'my-events', 'middleware' => 'auth:api'], function () {
    Route::get('/', [EventController::class, 'myevents']);
    Route::post('/{id}/check-in', [EventController::class, 'checkin']);
    Route::get('/{id}/participants', [EventController::class, 'participants']);
    Route::get('/{id}/kode-unik', [EventController::class, 'code']);
    Route::post('/{id}/cancel', [EventController::class, 'cancel']);
    Route::get('/{id}/status', [EventController::class, 'status']);
});