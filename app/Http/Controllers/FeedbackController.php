<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index()
    {
        // Selecionar os feedbacks visíveis e ordenar por data de criação
        $feedbacks = Feedback::where('visible', true)
                    ->orderBy('created_at', 'desc')
                    ->get();

        // Passar os feedbacks para a view
        return view('feedback.index', compact('feedbacks'));
    }

    public function create()
    {
        return view('feedback.create');
    }

    public function show()
    {
        return view('feedback.emoji');
    }

    public function store(Request $request)
    {
        // Validação dos campos
        $request->validate([
            'feedback' => 'required|string',  // Feedback selecionado
            'nome' => 'nullable|string|max:50',  // Nome opcional
            'avaliacao' => 'nullable|string|max:255',  // Avaliação opcional
        ]);
    
        // Verifique se o feedback já foi enviado anteriormente (opcional)
        if ($request->session()->has('feedbackSubmitted')) {
            return response()->json([
                'success' => false,
                'message' => 'Feedback já enviado anteriormente.'
            ]);
        }
    
        // Criar o feedback
        Feedback::create([
            'feedback' => $request->feedback,  // Feedback (ruim, médio, bom)
            'nome' => $request->nome,  // Nome (opcional)
            'avaliacao' => $request->avaliacao,  // Avaliação (opcional)
            'visible' => true,  // Define se o feedback será visível ou não
        ]);
    
        // Marcar o feedback como enviado (para evitar duplicação)
        $request->session()->put('feedbackSubmitted', true);
    
        return response()->json([
            'success' => true,
            'message' => 'Feedback enviado com sucesso!'
        ]);
    }
    
    



}
