<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class WebUsersController extends Controller
{
    public function showLoginForm()
    {
        return view('login'); // ou o nome da sua view de login
    }

    public function listUsers(Request $request)
    {
        getUserAdminOrRedirect($request);

        $users = User::get();

        return view('listusers', compact('users'));
    }

    /**
     * Exibe a tela de login.
     */
    public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        // Gera token se estiver usando Laravel Sanctum
        $token = $user->createToken('authToken')->plainTextToken;

        // Salva o ID do usuário na sessão
        session()->put('userid', $user->id);
        session()->save();

        return response()->json([
            'status' => true,
            'message' => 'Login realizado com sucesso',
            'token' => $token,
            'user' => $user,
            'redirect_url' => url('/painel')
        ]);
    }

    return response()->json([
        'status' => false,
        'error' => 'Usuário ou senha inválidos.'
    ], 401);
}

/**
     * Efetua o logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.form'); // ou ajuste conforme sua rota de login
    }

    /**
     * Exibe a tela de cadastro de usuário.
     */
    public function cadastro()
    {
        return view('cadastro');
    }


}