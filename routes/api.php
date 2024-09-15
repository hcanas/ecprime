<?php

use App\Http\Controllers\Api\CartItemController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\MainCategorySuggestionController;
use App\Http\Controllers\Api\MeasurementUnitSuggestionController;
use App\Http\Controllers\Api\SubCategorySuggestionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::name('api.')->group(function () {
    Route::get('categories', CategoryController::class)->name('categories');
    Route::get('main_categories', MainCategorySuggestionController::class)->name('main_categories');
    Route::get('sub_categories', SubCategorySuggestionController::class)->name('sub_categories');
    Route::get('measurement_units', MeasurementUnitSuggestionController::class)->name('measurement_units');
    Route::get('cart_items', CartItemController::class)->name('cart_items');
});
