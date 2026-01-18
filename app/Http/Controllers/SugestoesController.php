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

        $query = Sugestao::query()->latest('created_at');

        if ($apenasNaoRespondidas) {
            $query->naoRespondidas();
        }

        $sugestoes = $query->limit(12)->get();
        $totalGeral = Sugestao::count(); 

        return view('dashboard.sugestoes', [
            'sugestoes' => $sugestoes,
            'totalGeral' => $totalGeral,
            'apenasNaoRespondidas' => $apenasNaoRespondidas,
        ]);
    }

    // Método de API (JSON) para o AJAX
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
        $sugestao = Sugestao::findOrFail($id);
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

    // --- NOVOS MÉTODOS PARA RESPOSTA ---

    /**
     * Exibe a tela de resposta (GET)
     */
    public function responder($id)
    {
        $sugestao = Sugestao::findOrFail($id);
        

        return view('dashboard.sugestoes.responder', compact('sugestao'));
    }


    public function update(Request $request, $id)
    {
        $sugestao = Sugestao::findOrFail($id);

        $request->validate([
            'resposta' => 'required|string|min:3',
        ]);

        // Preenche EXATAMENTE os campos da sua imagem
        $sugestao->update([
            'conteudo_resposta' => $request->resposta,
            
            'respondido'        => true,          // Coluna tinyint(1)
            'data_resposta'     => now(),         // Coluna datetime
            
            'id_user_responded' => Auth::id(),    // Coluna bigint
            'respondido_por'    => Auth::user()->name ?? 'Admin', // Coluna varchar(255)
            'modificado_por'    => Auth::user()->name ?? 'Admin'  // Coluna varchar(255)
        ]);

        return redirect()
            ->route('dashboard.sugestoes')
            ->with('success', 'Resposta enviada e registrada com sucesso!');
    
    }
}