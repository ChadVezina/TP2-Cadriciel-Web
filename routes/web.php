<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EtudiantController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::get('/', [EtudiantController::class, 'index'])->name('home');

// Protected routes - require authentication
Route::middleware('auth')->group(function () {
    Route::resource('etudiants', EtudiantController::class)->except(['index', 'show']);
});

// Public routes - no authentication required
Route::resource('etudiants', EtudiantController::class)->only(['index', 'show']);

// Auth resource routes - using only create, store, destroy for login/logout
Route::resource('auth', AuthController::class)->only(['create', 'store']);
Route::delete('/auth', [AuthController::class, 'destroy'])->name('auth.destroy');

// Alternative logout route for convenience (GET instead of DELETE)
Route::get('/logout', [AuthController::class, 'destroy'])->name('logout');

// Named route aliases for backward compatibility
Route::get('/login', [AuthController::class, 'create'])->name('login');

// Users resource routes
Route::resource('users', UserController::class);

// Named route alias for registration (backward compatibility)
Route::get('/registration', [UserController::class, 'create'])->name('registration');
