<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EtudiantController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group.
|
*/

// Home route
Route::get('/', [EtudiantController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Locale Routes
|--------------------------------------------------------------------------
*/
Route::get('/locale/{locale}', [LocaleController::class, 'change'])
    ->name('locale.change')
    ->whereIn('locale', ['fr', 'en']);

/*
|--------------------------------------------------------------------------
| Guest Routes (Unauthenticated Users)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // Authentication routes
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('auth.store');
    
    // Registration routes
    Route::get('/registration', [UserController::class, 'create'])->name('registration');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Logout
    Route::delete('/logout', [AuthController::class, 'destroy'])->name('auth.destroy');
    Route::get('/logout', [AuthController::class, 'destroy'])->name('logout');
    
    // Students management (authenticated only)
    Route::resource('etudiants', EtudiantController::class)
        ->except(['index', 'show']);
    
    // Articles management
    Route::prefix('articles')->name('articles.')->group(function () {
        Route::get('/view-locale/{locale}', [ArticleController::class, 'changeViewLocale'])
            ->name('viewlocale.change')
            ->whereIn('locale', ['fr', 'en']);
    });
    
    Route::resource('articles', ArticleController::class)
        ->except(['index', 'show']);
    
    // Documents management (fully authenticated)
    Route::resource('documents', DocumentController::class);
    
    // Users management
    Route::resource('users', UserController::class)
        ->except(['create', 'store']);
});

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
// Public student routes
Route::resource('etudiants', EtudiantController::class)
    ->only(['index', 'show']);

// Public article routes
Route::resource('articles', ArticleController::class)
    ->only(['index', 'show']);
