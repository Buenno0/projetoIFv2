<?php
namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use App\Mail\WelcomeEmail; 
use Illuminate\Support\Facades\Mail; 
use Illuminate\Support\Facades\Log;


class UserController extends Controller
{
    public function index()
    {
        // Busca todos os usuários
        $users = User::all();

        // Passa a variável $users para a view
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
{
    $validatedData = $request->validate([
        'name' => 'required|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8|confirmed',
    ]);

    $validatedData['password'] = bcrypt($validatedData['password']);

    // Criar o usuário
    $user = User::create($validatedData);

    // Tentar enviar o e-mail
    try {
        Mail::to($user->email)->send(new WelcomeEmail($user));
    } catch (Exception $e) {
        // Registrar o erro no log
        Log::error('Erro ao enviar o e-mail de boas-vindas: ' . $e->getMessage());

        // Retornar uma mensagem de erro para o usuário
        return redirect()->route('users.index')->with('error', 'Usuário criado, mas ocorreu um erro ao enviar o e-mail.');
    }

    // Continuar com o fluxo normal
    return redirect()->route('users.index')->with('success', 'Usuário criado com sucesso e e-mail enviado!');
}

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
        ]);

        $user->update($request->all());

        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuário deletado com sucesso.');
    }
}
