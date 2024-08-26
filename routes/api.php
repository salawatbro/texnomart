<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

Route::post('/auth', [Controllers\UserController::class, 'auth']);
Route::post('/send-otp-code', [Controllers\OTPController::class, 'sendOTPCode']);
Route::post('/check-user-exists', [Controllers\UserController::class, 'checkUserExists']);
Route::get('/products', [Controllers\ProductController::class, 'index']);
Route::post('/orders/calculate', [Controllers\OrderController::class, 'calculateOrderPrice']);

Route::middleware('auth:api')->group(function () {
    Route::get('/getme', [Controllers\UserController::class, 'getMe']);
    Route::post('/payment/{uuid}', [Controllers\PaymentController::class, 'pay'])->name('payment');
    Route::prefix('/orders')->group(function () {
        Route::get('/', [Controllers\OrderController::class, 'index'])->middleware('role:admin');
        Route::post('/', [Controllers\OrderController::class, 'store']);
        Route::post('/cancel/{order}', [Controllers\OrderController::class, 'cancel']);
        Route::get('/history', [Controllers\OrderController::class, 'history']);
        Route::get('/{order}', [Controllers\OrderController::class, 'show']);
        Route::put('/{order}', [Controllers\OrderController::class, 'changeStatus'])->middleware('role:admin');
        Route::delete('/{order}', [Controllers\OrderController::class, 'destroy'])->middleware('role:admin');
    });
    Route::prefix('/users')
        ->middleware('role:admin')
        ->group(function () {
            Route::get('/', [Controllers\UserController::class, 'index']);
        });
});
