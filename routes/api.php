<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SubjectReferentialController;
use App\Http\Controllers\Api\TrackingController;
use Illuminate\Support\Facades\Route;

// ── Auth publique ─────────────────────────────────────────────────────────────
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login',    [AuthController::class, 'login']);
});

// ── Routes protégées ──────────────────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Authentification
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::get('auth/me',      [AuthController::class, 'me']);

    // Référentiel PDF matière
    Route::post('subjects/{subject}/referential', [SubjectReferentialController::class, 'upload']);
    Route::get('subjects/{subject}/referential',  [SubjectReferentialController::class, 'download']);

    // Tracking anti-triche
    Route::post('contents/{content}/segment-token', [TrackingController::class, 'issueToken']);
    Route::post('contents/{content}/progress',      [TrackingController::class, 'recordSegment']);
    Route::get('contents/{content}/progress',       [TrackingController::class, 'getProgress']);
});
