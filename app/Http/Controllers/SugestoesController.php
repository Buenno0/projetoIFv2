<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sugestao;
use App\Http\Resources\SugestaoResource;
use Illuminate\Support\Facades\Auth;

class SugestoesController extends Controller
{
    public function index(Request $request)
    {
        $apenasNaoRespondidas = $request->boolean('apenas_nao_respondidas');

        // NÃO precisa mais de ->where('visible', true) ou escopo ->visiveis()
        // O Laravel SoftDeletes já traz apenas as ativas por padrão.
        $query = Sugestao::query()->latest('created_at');

        if ($apenasNaoRespondidas) {
            $query->naoRespondidas();
        }

        $sugestoes = $query->limit(12)->get();
        $totalGeral = Sugestao::count(); // Já conta apenas as não deletadas

        return view('dashboard.sugestoes', [
            'sugestoes' => $sugestoes,
            'totalGeral' => $totalGeral,
            'apenasNaoRespondidas' => $apenasNaoRespondidas,
        ]);
    }

    // Método de API (JSON)
    public function indexJson(Request $request)
    {
        $apenasNaoRespondidas = $request->boolean('apenas_nao_respondidas');
        $perPage = max(1, (int) $request->input('per_page', 12));
        $busca = trim((string) $request->input('q', ''));

        $query = Sugestao::query()->latest('created_at');

        if ($apenasNaoRespondidas) {
            $query->naoRespondidas();
        }

        if ($busca !== '') {
            $query->where(function($q) use ($busca) {
                $q->where('conteudo', 'like', "%{$busca}%")
                  ->orWhere('nome', 'like', "%{$busca}%");
            });
        }

        $paginator = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => SugestaoResource::collection($paginator->items()),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
            ],
        ]);
    }

    public function destroy($id)
    {
        // findOrFail: Se não achar (ou se já estiver deletado), retorna 404 automaticamente.
        $sugestao = Sugestao::findOrFail($id);

        // AÇÃO: O Laravel vai preencher o deleted_at 
        // e o Auditor vai registrar o evento 'deleted'.
        $sugestao->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sugestão removida com sucesso.',
            'id' => (int) $sugestao->id,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'sugestao' => 'required|string|max:1000',
            'email' => 'required|email',
        ]);

        // Removemos o 'visible' => true, pois não existe mais a coluna
        $sugestao = Sugestao::create([
            'conteudo' => $request->sugestao,
            'nome' => $request->nome ?? 'Anônimo',
            'email' => $request->email,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Sugestão adicionada com sucesso!',
            'data' => $sugestao,
        ]);
    }
}