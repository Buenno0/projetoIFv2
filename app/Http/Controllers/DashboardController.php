<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Sugestao;

class DashboardController extends Controller
{
    public function getGreeting()
    {
        $currentHour = Carbon::now()->format('H');

        if ($currentHour < 12) {
            return 'Bom dia';
        } elseif ($currentHour < 18) {
            return 'Boa tarde';
        } else {
            return 'Boa noite';
        }
    }

    public function showDashboard()
    {
        $greeting = $this->getGreeting();
        return view('dashboard.index', ['greeting' => $greeting]);
    }

    public function reportChart()
    {
        return view('dashboard.report-chart');
    }

    public function sugestoesDashboard()
    {
        $greeting = $this->getGreeting();

        // Se o escopo for local, a assinatura correta é scopeVisiveis no modelo.
        // No PHP, você chama como ->visiveis()
        $sugestoes = Sugestao::visiveis()
            ->latest()
            ->take(10) // se quiser as 10 mais recentes
            ->get();

        return view('dashboard.sugestoes', [
            'greeting'  => $greeting,
            'sugestoes' => $sugestoes,
        ]);
    }
}
