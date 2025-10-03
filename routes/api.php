<?php

use Rapidez\CustomerPricing\Http\Controllers\CustomerPricingController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->prefix('api')->group(function () {
    Route::middleware(['auth:magento-customer'])->group(function () {
        Route::post('customerprices', [CustomerPricingController::class, 'getCustomerPrices']);
    });
});
