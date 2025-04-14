<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Viagem;


class WebPainelController extends Controller
{
    /**
     * Exibe o painel principal do sistema.
     */
    public function index(Request $request)
    {
        $userId = getUserIdOrRedirect($request);

        // Verifica se existe alguma viagem com visto = 0 para este usuário
        $existeAtualizacao = Viagem::where('user_id', $userId)
                                   ->where('visto', 0)
                                   ->exists();

        return view('painel', compact('existeAtualizacao'));
    }
}
