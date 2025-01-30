<?php

use App\Http\Controllers\Api\BillingController;
use App\Http\Controllers\Api\HistoryBankController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/billing-ukt', [BillingController::class, 'store_billing_ukt']);
Route::post('/history-bank', [HistoryBankController::class, 'auto_lunas']);
