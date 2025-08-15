<?php

namespace App\Http\Controllers;

use App\Models\Sugestoes;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Sugestao; // Model das sugestões (ajuste o nome se for diferente)

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

        // Buscar as sugestões mais recentes (por exemplo, últimas 10)
        $sugestoes = Sugestoes::latest()->get();

        // Retorna para resources/views/dashboard/sugestoes.blade.php
        return view('dashboard.sugestoes', [
            'greeting'   => $greeting,
            'sugestoes'  => $sugestoes

        ]);
    }
}
