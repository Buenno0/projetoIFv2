<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sugestao;
use App\Http\Resources\SugestaoResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\SugestaoRespondida;
use Illuminate\Support\Facades\Log;
use Appp\Mail\SugestaoEmAnalise;

class SugestoesController extends Controller
{

    public function indexJson(Request $request)
    {
        $perPage = max(1, (int) $request->input('per_page', 12));
        $busca   = trim((string) $request->input('q', ''));
        $status  = $request->input('status'); // 'pendente', 'em_analise', 'respondida'
        $filtro  = $request->input('filtro'); // 'minhas', 'todas'
        $ordem   = $request->input('order', 'desc'); // 'asc', 'desc'

        $query = Sugestao::query();

        // 1. Filtro de Status Específico
        if (!empty($status)) {
            $query->where('status', $status);
        }

        // 2. Filtro de "Minhas Análises" (Contexto do usuário logado)
        if ($filtro === 'minhas') {
            $query->where('id_user_analysing', Auth::id())
                  ->where('status', 'em_analise');
        }

        // 3. Busca Textual
        if ($busca !== '') {
            $query->where(function($q) use ($busca) {
                $q->where('conteudo', 'like', "%{$busca}%")
                  ->orWhere('nome', 'like', "%{$busca}%")
                  ->orWhere('id', 'like', "%{$busca}%");
            });
        }

        // 4. Ordenação
        $query->orderBy('created_at', $ordem);

        // Paginação
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
    
    // Atualize também o index principal para passar valores padrão se necessário
    public function index(Request $request)
    {
        // Carrega apenas a contagem total inicial
        $totalGeral = Sugestao::count(); 
        // Não carregamos a lista aqui, pois o JS vai buscar via AJAX na carga da página
        // mas passamos uma lista vazia ou a primeira página para o SEO/No-JS fallback se quiser
        $sugestoes = Sugestao::latest()->paginate(12);

        return view('dashboard.sugestoes', [
            'sugestoes' => $sugestoes,
            'totalGeral' => $totalGeral,
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
    // O 'with' já carrega os relacionamentos para evitar N+1 queries
    $sugestao = Sugestao::with(['usuarioQueAnalisou', 'usuarioQueRespondeu'])->findOrFail($id);

    // Acessando os cargos (utilize o operador ?-> para evitar erros caso seja null)
    $cargoAnalista = $sugestao->usuarioQueAnalisou?->role;
    $cargoRespondente = $sugestao->usuarioQueRespondeu?->role;

    // Você pode passar para a view
    return view('dashboard.sugestoes.responder', compact('sugestao', 'cargoAnalista', 'cargoRespondente'));
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

            // --- NOVO CÓDIGO: Enviar E-mail de "Em Análise" ---
            if (!empty($sugestao->email)) {
                try {
                    // Usa a nova classe de email criada
                    Mail::to($sugestao->email)->send(new \App\Mail\SugestaoEmAnalise($sugestao));
                } catch (\Exception $e) {
                    // Loga o erro mas não para o fluxo (o usuário não precisa saber que o email falhou)
                    Log::error("Erro ao enviar email de início de análise: " . $e->getMessage());
                }
            }
            // --------------------------------------------------

            return redirect()->route('sugestoes.responder', $id)
                ->with('success', 'Você assumiu a análise desta sugestão. O aluno foi notificado.');
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
