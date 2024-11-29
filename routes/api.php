<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\EventController;

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::patch('/user', [UserController::class, 'update'])->middleware('auth:sanctum');

Route::get('/user', [UserController::class, 'read'])->middleware('auth:sanctum');

Route::delete('/user', [UserController::class, 'delete'])->middleware('auth:sanctum');

Route::patch('/change-password', [UserController::class, 'change'])->middleware('auth:sanctum');

Route::get('/events/all', [EventController::class, 'index']);

Route::get('/events/webinar', [EventController::class, 'webinar']);

Route::get('/events/workshop', [EventController::class, 'workshop']);

Route::get('/events/seminar', [EventController::class, 'seminar']);

Route::get('/events/sertifikasi', [EventController::class, 'sertifikasi']);

Route::get('/events/kuliah-tamu', [EventController::class, 'kuliahtamu']);

Route::get('/events/{id}/view', [EventController::class, 'details']);

Route::get('/categories', [EventController::class, 'categories']);

Route::get('/my-events/all', [EventController::class, 'mine'])->middleware('auth:sanctum');

Route::get('/my-events/registered', [EventController::class, 'registered'])->middleware('auth:sanctum');

Route::get('/my-events/cancelled', [EventController::class, 'cancelled'])->middleware('auth:sanctum');

Route::get('/my-events/webinar', [EventController::class, 'mywebinar'])->middleware('auth:sanctum');

Route::get('/my-events/workshop', [EventController::class, 'myworkshop'])->middleware('auth:sanctum');

Route::get('/my-events/seminar', [EventController::class, 'myseminar'])->middleware('auth:sanctum');

Route::get('/my-events/sertifikasi', [EventController::class, 'mysertifikasi'])->middleware('auth:sanctum');

Route::get('/my-events/kuliah-tamu', [EventController::class, 'mykuliahtamu'])->middleware('auth:sanctum');

Route::get('/events/{id}/participants/all', [EventController::class, 'participants'])->middleware('auth:sanctum');

Route::get('/events/{id}/participants/cancelled', [EventController::class, 'absent'])->middleware('auth:sanctum');

Route::get('/events/{id}/participants/registered', [EventController::class, 'attend'])->middleware('auth:sanctum');

Route::get('/events/{id}/kode-unik', [EventController::class, 'kode'])->middleware('auth:sanctum');

Route::post('/events/{id}/register', [EventController::class, 'register'])->middleware('auth:sanctum');

Route::post('/events/{id}/cancel', [EventController::class, 'cancel'])->middleware('auth:sanctum');

Route::post('/events', [EventController::class, 'create'])->middleware('auth:sanctum');

Route::post('/events/{id}/edit', [EventController::class, 'update'])->middleware('auth:sanctum');

Route::delete('/events/{id}', [EventController::class, 'delete'])->middleware('auth:sanctum');