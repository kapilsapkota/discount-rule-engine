<?php

use App\Http\Controllers\DiscountController;
use App\Services\DiscountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/discounts/apply', [DiscountController::class, 'applyDiscounts'])->name('apply.discounts');
