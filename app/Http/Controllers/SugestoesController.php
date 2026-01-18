<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sugestao;
use App\Http\Resources\SugestaoResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\SugestaoRespondida;
use Illuminate\Support\Facades\Log;

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

    // 1. VALIDAÇÃO ROBUSTA
    $request->validate([
        'resposta' => 'required|string|min:10|max:5000',
    ], [
        'resposta.required' => 'Por favor, escreva uma resposta.',
        'resposta.min'      => 'A resposta deve ser mais detalhada (mínimo de 10 caracteres).',
        'resposta.max'      => 'A resposta é muito longa (máximo de 5000 caracteres).',
    ]);

    // 2. ATUALIZAÇÃO (Igual ao anterior)
    $sugestao->update([
        'conteudo_resposta' => $request->resposta,
        'respondido'        => true,
        'data_resposta'     => now(),
        'id_user_responded' => Auth::id(),
        'respondido_por'    => Auth::user()->name ?? 'Admin',
        'modificado_por'    => Auth::user()->name ?? 'Admin'
    ]);

    // Lógica de E-mail (Mantenha a que você já fez)
    if (!empty($sugestao->email)) {
        try {
            \Illuminate\Support\Facades\Mail::to($sugestao->email)->send(new \App\Mail\SugestaoRespondida($sugestao));
        } catch (\Exception $e) {
            // Log silencioso se falhar
        }
    }

    // 3. RETORNO COM SESSÃO FLASH
    return redirect()
        ->route('sugestoes.responder', $id)
        ->with('success', 'Resposta enviada com sucesso!');
}

}