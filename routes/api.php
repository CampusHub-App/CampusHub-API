<?php

// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\API\AuthController;
// use App\Http\Controllers\API\UserController;
// use App\Http\Controllers\API\EventController;

// Route::post('/register', [AuthController::class, 'register']);

// Route::post('/login', [AuthController::class, 'login']);

// Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Route::post('/user', [UserController::class, 'update'])->middleware('auth:sanctum');

// Route::get('/user', [UserController::class, 'read'])->middleware('auth:sanctum');

// Route::delete('/user', [UserController::class, 'delete'])->middleware('auth:sanctum');

// Route::post('/change-password', [UserController::class, 'change'])->middleware('auth:sanctum');

// Route::get('/events', [EventController::class, 'index']);

// Route::get('/events/{id}/view', [EventController::class, 'details']);

// Route::get('/categories', [EventController::class, 'categories']);

// Route::get('/my-events/all', [EventController::class, 'mine'])->middleware('auth:sanctum');

// Route::get('/my-events/registered', [EventController::class, 'registered'])->middleware('auth:sanctum');

// Route::get('/my-events/cancelled', [EventController::class, 'cancelled'])->middleware('auth:sanctum');

// Route::get('/my-events/{id}', [EventController::class, 'filter'])->middleware('auth:sanctum');

// Route::get('/events/{id}/participants/all', [EventController::class, 'participants'])->middleware('auth:sanctum');

// Route::get('/events/{id}/participants/cancelled', [EventController::class, 'absent'])->middleware('auth:sanctum');

// Route::get('/events/{id}/participants/registered', [EventController::class, 'attend'])->middleware('auth:sanctum');

// Route::get('/events/{id}/kode-unik', [EventController::class, 'kode'])->middleware('auth:sanctum');

// Route::post('/events/{id}/register', [EventController::class, 'register'])->middleware('auth:sanctum');

// Route::post('/events/{id}/cancel', [EventController::class, 'cancel'])->middleware('auth:sanctum');

// Route::post('/events', [EventController::class, 'create'])->middleware('auth:sanctum');

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\EventController;

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout']);

Route::post('/user', [UserController::class, 'update']);

Route::get('/user', [UserController::class, 'read']);

Route::delete('/user', [UserController::class, 'delete']);

Route::post('/change-password', [UserController::class, 'change']);

Route::get('/events', [EventController::class, 'index']);

Route::get('/events/{id}/view', [EventController::class, 'details']);

Route::get('/categories', [EventController::class, 'categories']);

Route::get('/my-events/all', [EventController::class, 'mine']);

Route::get('/my-events/registered', [EventController::class, 'registered']);

Route::get('/my-events/cancelled', [EventController::class, 'cancelled']);

Route::get('/my-events/{id}', [EventController::class, 'filter']);

Route::get('/events/{id}/participants/all', [EventController::class, 'participants']);

Route::get('/events/{id}/participants/cancelled', [EventController::class, 'absent']);

Route::get('/events/{id}/participants/registered', [EventController::class, 'attend']);

Route::get('/events/{id}/kode-unik', [EventController::class, 'kode']);

Route::post('/events/{id}/register', [EventController::class, 'register']);

Route::post('/events/{id}/cancel', [EventController::class, 'cancel']);

Route::post('/events', [EventController::class, 'create']);