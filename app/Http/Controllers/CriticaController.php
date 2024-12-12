<?php

namespace App\Http\Controllers;

use App\Models\Critica;
use Illuminate\Http\Request;

class CriticaController extends Controller
{
    public function index()
    {
        $criticas = Critica::where('visible', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('criticas.index', compact('criticas'));
    }

    public function create()
    {
        return view('criticas.create');
    }

    public function store(Request $request)
    {
        // Validação
        $request->validate([
            'conteudo' => 'required|string|max:1000',  // Limite de caracteres (se necessário)
        ]);

        // Criar a crítica
        $critica = Critica::create([
            'conteudo' => $request->conteudo,
            'user_id' => auth()->id(), // assuming you have user authentication
            'visible' => true, // ou defina o valor padrão na migration
        ]);

        // Retorno de sucesso em formato JSON
        return response()->json([
            'success' => true,
            'message' => 'Crítica adicionada com sucesso!',
            'data' => $critica, // Retorne os dados da crítica, se necessário
        ]);
    }

}

