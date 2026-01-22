<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DenunciaController,
    FeedbackController,
    CriticaController,
    SugestoesController,
    ProfileController,
    DashboardController,
    ReportController,
    AuditController
};

/*
|--------------------------------------------------------------------------
| Rotas Públicas
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('index');
});

Route::get('/obrigado', function () {
    return response()->file(public_path('obrigado.html'));
})->name('obrigado');

// Sugestões (Público)
Route::get('/sugestoes', [SugestoesController::class, 'show'])->name('sugestoes.show');
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

// Denúncias e Contatos
Route::post('/save_denuncia', [DenunciaController::class, 'store']);
Route::get('/contatos', function () {
    return view('contatos.index');
});

/*
|--------------------------------------------------------------------------
| Rotas Autenticadas (Dashboard)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Principal
    Route::get('/dashboard', [DashboardController::class, 'showDashboard'])
        ->name('dashboard');

    Route::prefix('dashboard/sugestoes')->group(function () {
        
        // URL Final: dashboard/sugestoes/
        Route::get('/', [DashboardController::class, 'sugestoesDashboard'])->name('dashboard.sugestoes');

        Route::get('/json', [SugestoesController::class, 'indexJson'])->name('dashboard.sugestoes.json');
        Route::delete('/{id}', [SugestoesController::class, 'destroy'])->name('sugestoes.destroy');
        Route::get('/{id}/responder', [SugestoesController::class, 'responder'])->name('sugestoes.responder');
        Route::put('/{id}', [SugestoesController::class, 'update'])->name('sugestoes.update');
        // Adicione esta linha junto com suas outras rotas de sugestões
        Route::post('/sugestoes/{id}/iniciar-analise', [SugestoesController::class, 'iniciarAnalise'])->name('sugestoes.iniciar_analise');
    });

    // Auditorias
    Route::prefix('dashboard/auditoria')->group(function () {
        Route::get('/', [AuditController::class, 'index'])->name('audits.index');
        Route::get('/download', [AuditController::class, 'download'])->name('audits.download');
    });

    // Relatórios
    Route::get('/dashboard/report-chart', [DashboardController::class, 'reportChart'])
        ->name('dashboard.reportChart');

    // Perfil do Usuário
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});

// Outros
Route::get('/report-chart', [ReportController::class, 'index'])->name('report.chart');

require __DIR__.'/auth.php';