<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\EventController;

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::post('/user', [UserController::class, 'update'])->middleware('auth:sanctum');

Route::get('/user', [UserController::class, 'read'])->middleware('auth:sanctum');

Route::delete('/user', [UserController::class, 'delete'])->middleware('auth:sanctum');

Route::post('/change-password', [UserController::class, 'change'])->middleware('auth:sanctum');

Route::get('/events', [EventController::class, 'index']);

Route::get('/events/{id}/view', [EventController::class, 'details']);

Route::get('/categories', [EventController::class, 'categories']);

Route::get('/my-events', [EventController::class, 'mine'])->middleware('auth:sanctum');
