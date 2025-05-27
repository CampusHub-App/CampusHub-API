<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\EventController;

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login/user', [AuthController::class, 'login']);

Route::post('/login/admin', [AuthController::class, 'atmin']);

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');

Route::post('/user', [UserController::class, 'update'])->middleware('auth:api');

Route::get('/user', [UserController::class, 'read'])->middleware('auth:api');

Route::delete('/user', [UserController::class, 'delete'])->middleware('auth:api');

Route::patch('/change-password', [UserController::class, 'change'])->middleware('auth:api');

Route::get('/events/all', [EventController::class, 'index']);

Route::get('/events/webinar', [EventController::class, 'webinar']);

Route::get('/events/workshop', [EventController::class, 'workshop']);

Route::get('/events/seminar', [EventController::class, 'seminar']);

Route::get('/events/sertifikasi', [EventController::class, 'sertifikasi']);

Route::get('/events/kuliah-tamu', [EventController::class, 'kuliahtamu']);

Route::get('/events/{id}/view', [EventController::class, 'details']);

Route::get('/categories', [EventController::class, 'categories']);

Route::get('/my-events/all', [EventController::class, 'mine'])->middleware('auth:api');

Route::get('/my-events/registered', [EventController::class, 'registered'])->middleware('auth:api');

Route::get('/my-events/cancelled', [EventController::class, 'cancelled'])->middleware('auth:api');

Route::get('/my-events/webinar', [EventController::class, 'mywebinar'])->middleware('auth:api');

Route::get('/my-events/workshop', [EventController::class, 'myworkshop'])->middleware('auth:api');

Route::get('/my-events/seminar', [EventController::class, 'myseminar'])->middleware('auth:api');

Route::get('/my-events/sertifikasi', [EventController::class, 'mysertifikasi'])->middleware('auth:api');

Route::get('/my-events/kuliah-tamu', [EventController::class, 'mykuliahtamu'])->middleware('auth:api');

Route::get('/events/{id}/participants/all', [EventController::class, 'participants'])->middleware('auth:api');

Route::get('/events/{id}/participants/cancelled', [EventController::class, 'absent'])->middleware('auth:api');

Route::get('/events/{id}/participants/registered', [EventController::class, 'attend'])->middleware('auth:api');

Route::get('/events/{id}/kode-unik', [EventController::class, 'kode'])->middleware('auth:api');

Route::post('/events/{id}/register', [EventController::class, 'register'])->middleware('auth:api');

Route::post('/events/{id}/cancel', [EventController::class, 'cancel'])->middleware('auth:api');

Route::post('/events', [EventController::class, 'create'])->middleware('auth:api');

Route::post('/events/{id}/edit', [EventController::class, 'update'])->middleware('auth:api');

Route::delete('/events/{id}', [EventController::class, 'delete'])->middleware('auth:api');

Route::get('/my-events/{id}/status', [EventController::class, 'status'])->middleware('auth:api');

Route::post('/my-events/{id}/check-in', [EventController::class, 'checkin'])->middleware('auth:api');