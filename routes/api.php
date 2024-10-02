<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\AuthController;

// 先頭にログインへのルーティングを記述
Route::post('/login', [AuthController::class, 'login'])->name('login');

// 当初ユーザー取得を通して、認証とリクエストの簡易的な検証とする
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', [UserController::class, 'index']); // user一覧取得
});