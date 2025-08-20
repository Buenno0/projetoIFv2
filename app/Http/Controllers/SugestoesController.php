<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sugestao;
use App\Http\Resources\SugestaoResource;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SugestoesController extends Controller
{

     public function show()
    {
        $sugestoes = Sugestao::where('visible', true)
            ->orderBy('created_at', 'desc')
            ->get();
            return view('sugestoes.index', compact('sugestoes'));
    }

    public function index(Request $request)
{
    $apenasNaoRespondidas = $request->boolean('apenas_nao_respondidas');

    $query = Sugestao::query()
        ->visiveis()               // aplica o escopo
        ->latest('created_at');

    if ($apenasNaoRespondidas) {
        $query->naoRespondidas();  // usa também o escopo local já definido
    }

    // Na render inicial, carregue algumas para fallback
    $sugestoes = $query->limit(12)->get();

    // Total geral apenas das visíveis (se quiser manter o total de todas, use Sugestao::count())
    $totalGeral = Sugestao::visiveis()->count();

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
    $page = max(1, (int) $request->input('page', 1));
    $busca = trim((string) $request->input('q', ''));

    $query = Sugestao::query()
        ->visiveis()               // aplica o escopo
        ->latest('created_at');

    if ($apenasNaoRespondidas) {
        $query->naoRespondidas();  // escopo local
    }

    if ($busca !== '') {
        $query->where(function($q) use ($busca) {
            $q->where('conteudo', 'like', "%{$busca}%")
              ->orWhere('nome', 'like', "%{$busca}%");
        });
    }

    $paginator = $query->paginate($perPage, ['*'], 'page', $page);

    // Se quiser metadado do total geral apenas de visíveis:
    $totalGeral = Sugestao::visiveis()->count();

    return response()->json([
        'success' => true,
        'data' => SugestaoResource::collection($paginator->items()),
        'meta' => [
            'current_page' => $paginator->currentPage(),
            'per_page'     => $paginator->perPage(),
            'total'        => $paginator->total(),
            'last_page'    => $paginator->lastPage(),
            'total_geral'  => $totalGeral,
            'apenas_nao_respondidas' => $apenasNaoRespondidas,
            'q' => $busca,
        ],
    ]);
}

     public function destroy(Request $request, $id)
    {
        $sugestao = Sugestao::find($id);

        if (!$sugestao || !$sugestao->visible) {
            return response()->json([
                'success' => false,
                'message' => 'Sugestão não encontrada ou já excluída.',
            ], 404);
        }

        $sugestao->visible = false;
        $sugestao->deleted_at = Carbon::now();
        $sugestao->id_user_deleted = Auth::id();

        $sugestao->save();

        return response()->json([
            'success' => true,
            'message' => 'Sugestão removida (soft delete) com sucesso.',
            'id' => (int) $sugestao->id,
        ]);
    }

       public function create()
    {
        return view('sugestoes.create');
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
            'visible' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Sugestão adicionada com sucesso!',
            'data' => $sugestao,
        ]);
    }



}
