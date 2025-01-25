<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/billing-ukt', [App\Http\Controllers\Api\BillingController::class, 'store_billing_ukt']);
