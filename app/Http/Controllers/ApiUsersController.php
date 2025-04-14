<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class ApiUsersController extends Controller
{
    public function store(Request $request)
    {
        // Validação básica
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        // Criação do usuário
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            //'perfil' => $validated['perfil'],
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Usuário criado com sucesso!',
            'user' => $user,
            'redirect_url' => url('/users/login') // Redirecionamento por JS no front
        ], 201);
    }
}