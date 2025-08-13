<?php

namespace App\Http\Controllers;

use App\Models\Sugestoes;
use Illuminate\Http\Request;
use App\Jobs\EnviarSugestaoEmail;

class SugestoesController extends Controller
{
public function index()
{
$sugestoes = Sugestoes::where('visible', true)
->orderBy('created_at', 'desc')
->get();

return view('sugestoes.index', compact('sugestoes'));
}

public function create()
{
return view('sugestoes.create');
}

public function store(Request $request)
{
// Validação
$request->validate([
'sugestao' => 'required|string|max:1000', // Alterar para o nome correto
'email' => 'required|email', // Validação do e-mail
]);

// Criar a sugestão
$sugestao = Sugestoes::create([
'conteudo' => $request->sugestao,
'nome' => $request->nome,
'email' => $request->email,
'visible' => true, // ou defina o valor padrão na migration
]);

// Despachar a job para enviar o e-mail
EnviarSugestaoEmail::dispatch($sugestao);

// Retorno de sucesso em formato JSON
return response()->json([
'success' => true,
'message' => 'Sugestão adicionada com sucesso!',
'data' => $sugestao, // Retorne os dados da sugestão, se necessário
]);
}
}
