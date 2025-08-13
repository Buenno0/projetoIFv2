<?php

use App\Http\Controllers\DenunciaController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\CriticaController;
use App\Http\Controllers\SugestoesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// Página inicial
Route::get('/', function () {
    return view('index');
});

// Página de obrigado
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
Route::post('/feedback_user', [FeedbackController::class, 'processForm'])->name('feedback.store');
Route::get('/feedback_emoji', [FeedbackController::class, 'show'])->name('feedback.emoji');
Route::post('/feedback_emoji', [FeedbackController::class, 'submitExperience'])->name('feedback.submitExperience');

// Denúncias
Route::post('/save_denuncia', [DenunciaController::class, 'store']);

// Contatos
Route::get('/contatos', function () {
    return view('contatos.index');
});

// Rotas protegidas por login e (opcionalmente) e-mail verificado
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard principal
    Route::get('/dashboard', [DashboardController::class, 'showDashboard'])
        ->name('dashboard');

    // Relatórios no dashboard
    Route::get('/dashboard/report-chart', [DashboardController::class, 'reportChart'])
        ->name('dashboard.reportChart');

    // Se quiser rota /report-chart separada mas protegida
    Route::get('/report-chart', [ReportController::class, 'index'])
        ->name('report.chart');

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rotas de autenticação Breeze
require __DIR__.'/auth.php';
