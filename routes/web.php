<?php

use App\Http\Controllers\DenunciaController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\CriticaController;
use App\Http\Controllers\SugestoesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use OwenIt\Auditing\Auditor;
use OwenIt\Auditing\Models\Audit;



// Página inicial
Route::get('/', function () {
    return view('index');
});

// Página de obrigado
Route::get('/obrigado', function () {
    return response()->file(public_path('obrigado.html'));
})->name('obrigado');

// Sugestões (públicas)
Route::get('/sugestoes', [SugestoesController::class, 'show'])->name('sugestoes.show');
Route::get('/sugestoes_user', [SugestoesController::class, 'create'])->name('sugestoes.create');
Route::post('/sugestoes_user', [SugestoesController::class, 'store'])->name('sugestoes.store');

// Críticas (públicas)
Route::get('/criticas', [CriticaController::class, 'index']);
Route::get('/criticas_user', [CriticaController::class, 'create'])->name('criticas.create');
Route::post('/criticas_user', [CriticaController::class, 'store'])->name('criticas.store');

// Feedback (público)
Route::get('/feedback', [FeedbackController::class, 'index']);
Route::get('/feedback_user', [FeedbackController::class, 'create'])->name('feedback.create');
Route::post('/feedback_user', [FeedbackController::class, 'processForm'])->name('feedback.store');
Route::get('/feedback_emoji', [FeedbackController::class, 'show'])->name('feedback.emoji');
Route::post('/feedback_emoji', [FeedbackController::class, 'submitExperience'])->name('feedback.submitExperience');

// Denúncias (público)
Route::post('/save_denuncia', [DenunciaController::class, 'store']);

// Contatos (público)
Route::get('/contatos', function () {
    return view('contatos.index');
});

// Protegido apenas por login
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'showDashboard'])
        ->name('dashboard');

    // Sugestões no dashboard
    Route::get('/dashboard/sugestoes', [DashboardController::class, 'sugestoesDashboard'])
    ->name('dashboard.sugestoes');

    Route::get('/dashboard/sugestoes/json', [\App\Http\Controllers\SugestoesController::class, 'indexJson'])
    ->name('dashboard.sugestoes.json');

    Route::get('/auditorias', function () {
    $audits = Audit::with(['user', 'auditable'])->latest()->get();
    return view('auditorias.index', compact('audits'));
});


    


Route::get('/sugestoes/{id}/responder', [SugestoesController::class, 'responder'])
    ->name('sugestoes.responder');



Route::delete('/sugestoes/{id}', [SugestoesController::class, 'destroy'])->name('sugestoes.destroy');




    // Relatório dentro do dashboard
    Route::get('/dashboard/report-chart', [DashboardController::class, 'reportChart'])
        ->name('dashboard.reportChart');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rota de relatório pública (fora do dashboard, opcional)
Route::get('/report-chart', [ReportController::class, 'index'])->name('report.chart');

// Rotas de autenticação Breeze
require __DIR__.'/auth.php';
