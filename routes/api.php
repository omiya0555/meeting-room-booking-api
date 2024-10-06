<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\BookingStatusController;
use App\Http\Controllers\Api\BookingHistoryController;
use App\Http\Controllers\Api\CalendarEventController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\AuthController;

// 先頭にログインへのルーティングを記述
Route::post('/login', [AuthController::class, 'login'])->name('login');

// 当初ユーザー取得を通して、認証とリクエストの簡易的な検証とする
Route::middleware('auth:sanctum')->group(function () {

    // user情報
    Route::get('/users', [UserController::class, 'index']); // user一覧取得
    Route::get('/users/{id}', [UserController::class, 'show']); // ユーザー詳細
    Route::post('/users', [UserController::class, 'store']);    // ユーザー作成
    Route::put('/users/{id}', [UserController::class, 'update']); // ユーザー更新
    Route::delete('/users/{id}', [UserController::class, 'destroy']); // ユーザー削除

    // booking情報
    Route::get('/bookings', [BookingController::class, 'index']); 
    Route::get('/bookings/{id}', [BookingController::class, 'show']);
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::put('/bookings/{id}', [BookingController::class, 'update']);
    Route::delete('/bookings/{id}', [BookingController::class, 'destroy']); 

    // booking status情報
    Route::get('/booking/status', [BookingStatusController::class, 'index']); 
    Route::get('/booking/status/{id}', [BookingStatusController::class, 'show']);
    Route::post('/booking/status', [BookingStatusController::class, 'store']);
    Route::put('/bookings/status/{id}', [BookingStatusController::class, 'update']);
    Route::delete('/bookings/status/{id}', [BookingStatusController::class, 'destroy']); 

    // booking history情報
    Route::get('/booking/history', [BookingHistoryController::class, 'index']); 
    Route::get('/booking/history/{id}', [BookingHistoryController::class, 'show']);
    Route::post('/booking/history', [BookingHistoryController::class, 'store']);
    Route::put('/booking/history/{id}', [BookingHistoryController::class, 'update']);
    Route::delete('/booking/history/{id}', [BookingHistoryController::class, 'destroy']); 

    // calendar events情報
    Route::get('/calendar', [CalendarEventController::class, 'index']); 
    Route::get('/calendar/{id}', [CalendarEventController::class, 'show']);
    // 月ごとの events情報
    Route::get('/calendar', [CalendarEventController::class, 'getEventsByMonth']);
    Route::post('/calendar', [CalendarEventController::class, 'store']);
    Route::put('/calendar/{id}', [CalendarEventController::class, 'update']);
    Route::delete('/calendar/{id}', [CalendarEventController::class, 'destroy']);

    // notification情報
    Route::get('/notification', [NotificationController::class, 'index']); 
    Route::get('/notification/{id}', [NotificationController::class, 'show']);
    Route::post('/notification', [NotificationController::class, 'store']);
    Route::put('/notification/{id}', [NotificationController::class, 'update']);
    Route::delete('/notification/{id}', [NotificationController::class, 'destroy']); 
    
    // room情報
    Route::get('/room', [RoomController::class, 'index']); 
    Route::get('/room/{id}', [RoomController::class, 'show']);
    Route::post('/room', [RoomController::class, 'store']);
    Route::put('/room/{id}', [RoomController::class, 'update']);
    Route::delete('/room/{id}', [RoomController::class, 'destroy']); 
});