<?php

use App\Http\Controllers\Administrator\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});