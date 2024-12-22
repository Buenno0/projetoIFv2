<?php

use App\Http\Controllers\DenunciaController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\CriticaController;
use App\Http\Controllers\SugestoesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;


use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/obrigado', function () {
    return response()->file(public_path('obrigado.html'));
})->name('obrigado');

// Sugestões
Route::get('/sugestoes', [SugestoesController::class, 'index']);
Route::get('/sugestoes_user', [SugestoesController::class, 'create'])->name('sugestoes.create');
Route::post('/sugestoes_user', [SugestoesController::class, 'store'])->name('sugestoes.store');

// Críticas
Route::get('/criticas', [CriticaController::class, 'index']);
Route::get('/criticas_user', [CriticaController::class, 'create'])->name('criticas.create');
Route::post('/criticas_user', [CriticaController::class, 'store'])->name('criticas.store');

// Feedback
Route::get('/feedback', [FeedbackController::class, 'index']);
Route::get('/feedback_user', [FeedbackController::class, 'create'])->name('feedback.create');
Route::post('/feedback_user', [FeedbackController::class, 'processForm'])->name('feedback.store'); // Mudança para processForm
Route::get('/feedback_emoji', [FeedbackController::class, 'show'])->name('feedback.emoji'); // Mudança para show
Route::post('/feedback_emoji', [FeedbackController::class, 'submitExperience'])->name('feedback.submitExperience'); // Nova rota para processar a experiência

// Denúncias
Route::post('/save_denuncia', [DenunciaController::class, 'store']);

// Contatos
Route::get('/contatos', function () {
    return view('contatos.index');
});

Route::get('/dashboard', function () {
    return view('dashboard.index');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/dashboard/report-chart', [DashboardController::class, 'reportChart'])->name('dashboard.reportChart');
Route::get('/dashboard/report-chart', [ReportController::class, 'index'])->name('dashboard.reportChart');




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard', [DashboardController::class, 'showDashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/report-chart', [ReportController::class, 'index']);




require __DIR__.'/auth.php';
