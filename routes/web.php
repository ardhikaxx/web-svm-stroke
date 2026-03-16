<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PrediksiController;

Route::get('/', [PrediksiController::class, 'index']);
Route::post('/upload', [PrediksiController::class, 'upload']);
Route::post('/import', [PrediksiController::class, 'importToDatabase']);
Route::post('/prediksi', [PrediksiController::class, 'prediksi']);
Route::get('/stats', [PrediksiController::class, 'getStats']);
Route::get('/data-terbaru', [PrediksiController::class, 'getDataTerbaru']);
Route::post('/hapus-data', [PrediksiController::class, 'hapusData']);
