<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\LoanCalculationController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/loans', [LoanController::class, 'index']);
    Route::post('/loans', [LoanController::class, 'store']);
    Route::get('/loans/{loan}', [LoanController::class, 'show']);

    Route::post('/loan/calculate', [LoanCalculationController::class, 'calculate']);
    Route::post('/loan/recalculate', [LoanCalculationController::class, 'recalculate']);

});

require __DIR__.'/auth.php';


