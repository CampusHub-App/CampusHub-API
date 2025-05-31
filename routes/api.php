<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\EventController;

Route::group(['prefix' => 'auth'], function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login/user', [AuthController::class, 'login']);
    Route::post('/login/admin', [AuthController::class, 'atmin']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::patch('/change-password', [UserController::class, 'change'])->middleware('auth:api');
});

Route::group(['prefix' => 'user', 'middleware' => 'auth:api'], function () {
    Route::post('/', [UserController::class, 'update']);
    Route::get('/', [UserController::class, 'read']);
    Route::delete('/', [UserController::class, 'delete']);
});

Route::group(['prefix' => 'events'], function () {
    Route::get('/', [EventController::class, 'index']);
    Route::get('/{id}/view', [EventController::class, 'details']);
});

Route::group(['prefix' => 'events', 'middleware' => 'auth:api'], function () {
    Route::get('/{id}/participants', [EventController::class, 'participants']);
    Route::get('/{id}/participants/cancelled', [EventController::class, 'absent']);
    Route::get('/{id}/participants/registered', [EventController::class, 'attend']);
    Route::get('/{id}/kode-unik', [EventController::class, 'kode']);
    Route::post('/{id}/register', [EventController::class, 'register']);
    Route::post('/{id}/cancel', [EventController::class, 'cancel']);
    Route::post('/', [EventController::class, 'create']);
    Route::put('/{id}/edit', [EventController::class, 'update']);
    Route::delete('/{id}', [EventController::class, 'delete']);
});

Route::group(['prefix' => 'my-events', 'middleware' => 'auth:api'], function () {
    Route::get('/all', [EventController::class, 'mine']);
    Route::get('/registered', [EventController::class, 'registered']);
    Route::get('/cancelled', [EventController::class, 'cancelled']);
    Route::get('/webinar', [EventController::class, 'mywebinar']);
    Route::get('/workshop', [EventController::class, 'myworkshop']);
    Route::get('/seminar', [EventController::class, 'myseminar']);
    Route::get('/sertifikasi', [EventController::class, 'mysertifikasi']);
    Route::get('/kuliah-tamu', [EventController::class, 'mykuliahtamu']);
    Route::get('/{id}/status', [EventController::class, 'status']);
    Route::post('/{id}/check-in', [EventController::class, 'checkin']);
});