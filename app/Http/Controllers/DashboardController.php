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

   public function sugestoesDashboard(\Illuminate\Http\Request $request)
{
    $greeting = $this->getGreeting();

    $query = \App\Models\Sugestao::visiveis()->latest();

    if ($request->boolean('apenas_nao_respondidas')) {
        $query->where(function ($q) {
            $q->where('respondido', false)->orWhereNull('respondido');
        });
    }

    $sugestoes = $query->get();
    $totalGeral = \App\Models\Sugestao::visiveis()->count();

    return view('dashboard.sugestoes', [
        'greeting'             => $greeting,
        'sugestoes'            => $sugestoes,
        'apenasNaoRespondidas' => $request->boolean('apenas_nao_respondidas'),
        'totalGeral'           => $totalGeral,
    ]);
}


}
