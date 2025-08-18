<?php

namespace App\Http\Controllers;

use App\Models\Sugestao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class SugestoesController extends Controller
{
    public function index()
    {
        $sugestoes = Sugestao::where('visible', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('sugestoes.index', compact('sugestoes'));
    }

    // public function indexDashboard()
    // {
    //     $sugestoes = Sugestoes::visiveis()
    //         ->orderBy('created_at', 'desc')
    //         ->get();

    //     return view('dashboard.sugestoes', compact('sugestoes'));
    // }

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

    // NOVO: Soft delete via AJAX
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
}
