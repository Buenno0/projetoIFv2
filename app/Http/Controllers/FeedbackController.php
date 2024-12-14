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

    public function processForm(Request $request)
    {
        // Validação dos campos
        $request->validate([
            'nome' => 'nullable|string|max:50',
            'avaliacao' => 'required|string|max:255',
        ]);

        // Armazenar os dados em sessão para uso na próxima página
        $request->session()->put('feedback', $request->only(['nome', 'avaliacao']));

        // Redirecionar para a página de escolha da experiência
        return redirect()->route('feedback.emoji');
    }

    public function submitExperience(Request $request)
    {
        // Validação da escolha da experiência
        $request->validate([
            'feedback' => 'required|in:ruim,medio,bom'
        ]);

        // Recuperar os dados armazenados na sessão
        $feedbackData = $request->session()->get('feedback');

        // Criar o feedback
        Feedback::create([
            'feedback' => $request->feedback,  // Feedback (ruim, médio, bom)
            'nome' => $feedbackData['nome'],  // Nome (opcional)
            'conteudo' => $feedbackData['avaliacao'],  // Avaliação
            'visible' => true,  // Define se o feedback será visível ou não
        ]);

        // Marcar o feedback como enviado (para evitar duplicação)
        $request->session()->put('feedbackSubmitted', true);

        // Redirecionar para a página de agradecimento
        return response()->json(['success' => true]);
    }
}
