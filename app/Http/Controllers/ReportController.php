<?php

namespace App\Http\Controllers;
use App\Models\Critica;
use App\Models\Feedback;
use App\Models\Sugestoes;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function index()
    {
        // Obter dados de críticas
        $criticisms = Critica::selectRaw('MONTHNAME(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderByRaw('MIN(created_at)')
            ->get();

        // Obter dados de feedbacks
        $feedbacks = Feedback::selectRaw('MONTHNAME(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderByRaw('MIN(created_at)')
            ->get();

        // Obter dados de sugestões
        $suggestions = Sugestoes::selectRaw('MONTHNAME(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderByRaw('MIN(created_at)')
            ->get();

        // Preparar dados para o gráfico
        $months = $criticisms->pluck('month')->merge($feedbacks->pluck('month'))->merge($suggestions->pluck('month'))->unique()->sortBy(function ($month) {
            return Carbon::parse($month)->month;
        });

        $criticismCounts = $months->mapWithKeys(function ($month) use ($criticisms) {
            return [$month => $criticisms->firstWhere('month', $month)->count ?? 0];
        });

        $feedbackCounts = $months->mapWithKeys(function ($month) use ($feedbacks) {
            return [$month => $feedbacks->firstWhere('month', $month)->count ?? 0];
        });

        $suggestionCounts = $months->mapWithKeys(function ($month) use ($suggestions) {
            return [$month => $suggestions->firstWhere('month', $month)->count ?? 0];
        });

        // Criar o gráfico
        $chart = new Chart();
        $chart->labels($months);

        
        $chart->dataset('Críticas', 'bar', $criticismCounts->values())
              ->backgroundColor('rgb(255, 99, 71)');
        $chart->dataset('Feedbacks', 'bar', $feedbackCounts->values())
              ->backgroundColor('rgb(144, 238, 144)')
              ->color('rgb(144, 238, 144)');
        $chart->dataset('Sugestões', 'bar', $suggestionCounts->values())
              ->backgroundColor('rgb(65, 105, 225)')
              ->color('rgb(65, 105, 225)');

        return view('dashboard.report-chart', compact('chart'));
    }
}
