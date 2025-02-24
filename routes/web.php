<?php

use App\Http\Controllers\DiscountController;
use App\Services\DiscountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('discounts', DiscountController::class);
