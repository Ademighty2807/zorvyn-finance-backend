<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FinancialRecordController;
use App\Http\Controllers\AnalyticsController;

Route::middleware('auth:sanctum',)->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::prefix('v1')->group(function () {
        Route::get('/test', function () {
            return response()->json(['message' => 'This is a test route']);
        });



        // All roles
        // routes/api.php — inside auth:sanctum middleware group

        Route::get('/analytics', [AnalyticsController::class, 'index']);
        Route::get('/records', [FinancialRecordController::class, 'index']);
        Route::get('/records/{financialRecord}', [FinancialRecordController::class, 'show']);

        // Admin & Accountant only
        Route::middleware('role:admin|accountant')->group(function () {
            Route::post('/records', [FinancialRecordController::class, 'store']);
            Route::put('/records/{financialRecord}', [FinancialRecordController::class, 'update']);
        });

        // Admin only
        Route::middleware('role:admin')->group(function () {
            Route::delete('/records/{financialRecord}', [FinancialRecordController::class, 'destroy']);
        });
    });
});




require __DIR__.'/auth.php';
