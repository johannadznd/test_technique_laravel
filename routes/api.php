<?php

use App\Http\Controllers\Administrator\AuthController;
use App\Http\Controllers\Profil\ProfilController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});


Route::middleware(['auth:administrator'])->group(function () {
    Route::post("/profil", [ProfilController::class, 'store']);
    Route::delete("/profil/{id}", [ProfilController::class, 'destroy']);
    Route::put("/profil/{id}", [ProfilController::class, 'update']);
});

Route::get("/profil", [ProfilController::class, 'index']);
Route::get("/profil/{id}", [ProfilController::class, 'show']);
