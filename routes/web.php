<?php

use App\Http\Controllers\DenunciaController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\CriticaController;
use App\Http\Controllers\SugestoesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/obrigado', function () {
    return response()->file(public_path('obrigado.html'));
})->name('obrigado');


Route::get('/sugestoes', [SugestoesController::class, 'index']);
Route::get('/criticas', [CriticaController::class, 'index']);

Route::get('/sugestoes_user', [SugestoesController::class, 'create'])->name('sugestoes.create');
Route::post('/sugestoes_user', [SugestoesController::class, 'store'])->name('sugestoes.store');
Route::get('/criticas_user', [CriticaController::class, 'create'])->name('criticas.create');
Route::post('/criticas_user', [CriticaController::class, 'store'])->name('criticas.store');


Route::get('/feedback', [FeedbackController::class, 'index']);
Route::post('/save_denuncia', [DenunciaController::class, 'store']);
