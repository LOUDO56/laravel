<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SubjectWebController;
use App\Http\Controllers\WebAuthController;
use App\Http\Controllers\ContentWebController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () { return view('welcome'); });

Route::get('/login', [WebAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [WebAuthController::class, 'login']);
Route::get('/register', [WebAuthController::class, 'showRegister'])->name('register');
Route::post('/register', [WebAuthController::class, 'register']);
Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');

// Vues Blade authentifiées
Route::middleware('auth')->group(function () {
    Route::get('/dashboard',       [DashboardController::class, 'index']);
    Route::get('/subjects',        [SubjectWebController::class, 'index']);
    Route::get('/subjects/{id}',   [SubjectWebController::class, 'show']);
    Route::get('/contents/{id}',   [ContentWebController::class, 'show']);
});
