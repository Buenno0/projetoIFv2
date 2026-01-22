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

    public function responder($id)
    {
        $sugestao = Sugestao::findOrFail($id);
        $sugestao = Sugestao::with(['usuarioQueAnalisou', 'usuarioQueRespondeu'])->findOrFail($id);

        return view('dashboard.sugestoes.responder', compact('sugestao'));
    }

    public function iniciarAnalise($id)
    {
        $sugestao = Sugestao::findOrFail($id);

        if ($sugestao->status === 'pendente' || (is_object($sugestao->status) && $sugestao->status->value === 'pendente')) {
            
            $sugestao->update([
                'status' => 'em_analise',
                'id_user_analysing' => Auth::id(), 
                'data_analise' => now(),
            ]);

            return redirect()->route('sugestoes.responder', $id)
                ->with('success', 'Você assumiu a análise desta sugestão.');
        }

        return redirect()->route('sugestoes.responder', $id)
            ->with('error', 'Esta sugestão já está em análise ou finalizada.');
    }

    public function update(Request $request, $id)
    {
        $sugestao = Sugestao::findOrFail($id);

        $request->validate([
            'resposta' => 'required|string|min:10|max:5000',
        ], [
            'resposta.required' => 'Por favor, escreva uma resposta.',
            'resposta.min'      => 'A resposta deve ser mais detalhada (mínimo de 10 caracteres).',
            'resposta.max'      => 'A resposta é muito longa (máximo de 5000 caracteres).',
        ]);

        $sugestao->update([
            'conteudo_resposta' => $request->resposta,
            'status'            => 'respondida', // Atualiza status final
            'data_resposta'     => now(),
            'id_user_responded' => Auth::id(),
            'respondido_por'    => Auth::user()->name ?? 'Admin',
            'modificado_por'    => Auth::user()->name ?? 'Admin'
        ]);

        if (!empty($sugestao->email)) {
            try {
                Mail::to($sugestao->email)->send(new SugestaoRespondida($sugestao));
            } catch (\Exception $e) {
                Log::error("Erro ao enviar email de sugestão respondida: " . $e->getMessage());
            }
        }

        return redirect()
            ->route('sugestoes.responder', $id)
            ->with('success', 'Resposta enviada com sucesso!');
    }
}
