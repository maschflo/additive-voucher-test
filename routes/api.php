<?php

use App\Http\Controllers\VoucherController;
use App\Http\Controllers\WebhookController;
use App\Http\Middleware\EnsureIdempotency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('/vouchers')->name('vouchers.')->middleware(EnsureIdempotency::class)->group(function () {
    Route::post('/issue', [VoucherController::class, 'issue'])->name('issue');
    Route::post('/redeem', [VoucherController::class, 'redeem'])->name('redeem');
});

Route::post('/hooks', [WebhookController::class, 'handle']);
