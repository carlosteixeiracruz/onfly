<?php

use Illuminate\Http\Request;
use App\Models\User;

/**
 * Verifica se o usuário está logado via sessão e retorna o ID.
 * Caso não esteja logado, redireciona para a rota de login.
 */
function getUserIdOrRedirect(Request $request)
{
    $userId = $request->session()->get('userid');

    if (!$userId) {
        return redirect('/users/login');
    }

    return $userId;
}

/**
 * Verifica se o usuário é admin.
 */
function getUserAdminOrRedirect(Request $request)
{
    $userId = $request->session()->get('userid');

    if (!$userId) {
        return redirect()->route('login.index');
    }

    $user = User::find($userId);

    if (!$user) {
        return redirect()->route('login.index');
    }

    // Verifica se o perfil é 2 (admin)
    if ($user->perfil == 2) {
        return $user->perfil;
    }

    return redirect()->route('login.index');
}

