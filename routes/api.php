<?php

use App\Http\Controllers\API\PageGeneral\MunicipalidadController;
use App\Http\Controllers\API\Dashboard\DashboardController;
use Illuminate\Support\Facades\Route;

// --- RUTAS PÚBLICAS BÁSICAS OMITIDAS ---


// --------- RUTAS LIBRES PARA TESTS ---------

// Dashboard summary (sin auth)
Route::get('dashboard/summary', [DashboardController::class, 'summary']);

// CRUD completo de municipalidades, en /api/v1/municipalidades
Route::prefix('v1')->group(function () {
    Route::get('municipalidades',         [MunicipalidadController::class,'index']);
    Route::get('municipalidades/{id}',    [MunicipalidadController::class,'show']);
    Route::post('municipalidades',        [MunicipalidadController::class,'store']);
    Route::put('municipalidades/{id}',    [MunicipalidadController::class,'update']);
    Route::delete('municipalidades/{id}', [MunicipalidadController::class,'destroy']);
});
