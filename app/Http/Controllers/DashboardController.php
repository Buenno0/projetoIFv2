<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sugestao; // Importação limpa
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Exibe a página inicial do dashboard.
     */
    public function showDashboard()
    {
        return view('dashboard.index', [
            'greeting' => $this->getGreeting()
        ]);
    }

    /**
     * Exibe o gráfico de relatórios.
     */
    public function reportChart()
    {
        return view('dashboard.report-chart');
    }

    /**
     * Exibe e filtra a lista de sugestões.
     */
    public function sugestoesDashboard(Request $request)
    {
        // 1. Query Builder Fluido
        $sugestoes = Sugestao::visiveis()
            ->latest() // Atalho para order by created_at desc
            ->when($request->boolean('apenas_nao_respondidas'), function ($query) {
                // Usa o escopo que criamos no Model (reutilização de código)
                $query->naoRespondidas();
            })
            ->paginate(12) // Resolve o erro do "Collection::total"
            ->withQueryString(); // Mantém os filtros na URL ao mudar de página

        // 2. Contagem separada (apenas visíveis)
        $totalGeral = Sugestao::visiveis()->count();

        return view('dashboard.sugestoes', [
            'greeting'             => $this->getGreeting(),
            'sugestoes'            => $sugestoes,
            'totalGeral'           => $totalGeral,
            'apenasNaoRespondidas' => $request->boolean('apenas_nao_respondidas'),
        ]);
    }

    /**
     * Helper privado para definir a saudação baseada na hora.
     */
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