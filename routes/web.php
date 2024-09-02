<?php

use App\Http\Controllers\SslCommerzeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::prefix('user')->group(function(){
    Route::get('register', [UserController::class, 'create']);
    Route::get('verify', [UserController::class, 'otpPage']);
    Route::get('login', [UserController::class, 'index']);
});

Route::prefix('payment')->group(function(){
    Route::post('success', [SslCommerzeController::class, 'PaymentSuccess']);
    Route::post('cancel', [SslCommerzeController::class, 'PaymentCancel']);
    Route::post('fail', [SslCommerzeController::class, 'PaymentFail']);
});