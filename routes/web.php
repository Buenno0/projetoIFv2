<?php

use App\Http\Controllers\DenunciaController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\CriticaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/criticas', [CriticaController::class, 'index']);
Route::get('/avaliacoes_user', [CriticaController::class, 'create'])->name('criticas.create');
Route::post('/avaliacoes_user', [CriticaController::class, 'store'])->name('criticas.store');

Route::get('/feedback', [FeedbackController::class, 'index']);
Route::post('/save_denuncia', [DenunciaController::class, 'store']);
