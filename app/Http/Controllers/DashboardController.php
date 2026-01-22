<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sugestao;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function showDashboard()
    {
        return view('dashboard.index', [
            'greeting' => $this->getGreeting()
        ]);
    }

    public function reportChart()
    {
        return view('dashboard.report-chart');
    }

    public function sugestoesDashboard(Request $request)
    {
        $sugestoes = Sugestao::visiveis()
            ->latest()
            ->when($request->boolean('apenas_nao_respondidas'), function ($query) {
                $query->naoRespondidas();
            })
            ->paginate(12)
            ->withQueryString();

        $totalGeral = Sugestao::visiveis()->count();

        return view('dashboard.sugestoes', [
            'greeting'             => $this->getGreeting(),
            'sugestoes'            => $sugestoes,
            'totalGeral'           => $totalGeral,
            'apenasNaoRespondidas' => $request->boolean('apenas_nao_respondidas'),
        ]);
    }

    private function getGreeting(): string
    {
        $hour = Carbon::now()->hour;
        return match (true) {
            $hour < 12 => 'Bom dia',
            $hour < 18 => 'Boa tarde',
            default    => 'Boa noite',
        };
    }
}
