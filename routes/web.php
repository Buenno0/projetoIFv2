<?php

use App\Http\Controllers\DenunciaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\CriticaController;

Route::get('/criticas', [CriticaController::class, 'index']);
Route::get('/feedback', [FeedbackController::class, 'index']);
Route::get('/', function () {
    return view('index');
});
Route::post('/save_denuncia', [DenunciaController::class, 'store']);


