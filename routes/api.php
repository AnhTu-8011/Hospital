<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AIChatController;
use App\Http\Controllers\AuthApiController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [AuthApiController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthApiController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

// API chat AI (public endpoint for chat widget)
Route::post('/chat/ai', [AIChatController::class, 'chat'])->name('api.chat.ai');
