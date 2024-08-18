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
}
