<?php

namespace App\Http\Controllers;

use App\Models\Pais;
use App\Models\Viagem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class WebViagemController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'    => 'required|exists:users,id',
            'pais_id'    => 'required|exists:pais,id',
            'data_ida'   => 'required|date',
            'data_volta' => 'required|date|after_or_equal:data_ida',
        ]);
        
        // Adiciona o campo 'visto' com valor padrão true
        $validated['visto'] = true;
        
        // Criar viagem
        $viagem = Viagem::create($validated);
        
        return response()->json([
            'status'  => true,
            'message' => 'Viagem registrada com sucesso!',
            'viagem'  => $viagem
        ], 201);        
    }

    public function edit(Request $request)
    {
        getUserAdminOrRedirect($request);

        $validated = $request->validate([
            'viagem_id' => 'required|exists:viagem,id',  // Usando 'id' como o nome da coluna
            'situacao' => 'required|integer|in:1,2,3',
        ]);

        // Atualiza a situação da viagem
        $viagem = \App\Models\Viagem::find($validated['viagem_id']);
        $viagem->situacao = $validated['situacao'];
        $viagem->visto = 0;
        $viagem->save();

        return response()->json([
            'status'  => true,
            'message' => 'Situação da viagem alterada com sucesso!'
        ], 200);
    }

    /**
     * Lista todas as viagens de determinado usuário.
     */
    public function pedidoViagem(Request $request)
    {
        // Obtém o ID do usuário atual
        $userId = getUserIdOrRedirect($request);

        // Retorna a view com o userId
        return view('viagem', ['userId' => $userId]);
    }

    public function listPedidoViagem()
    {
        $userId = auth()->id(); // Obtém o ID do usuário autenticado
    
        // Ordenando pelo campo 'visto' de forma que '0' apareça primeiro
        $viagens = Viagem::with('pais')
                    ->where('user_id', $userId)
                    ->orderBy('visto', 'asc')  // '0' primeiro, depois '1'
                     ->get();

        // Atualiza o campo 'visto' para 1 onde for 0
        Viagem::where('user_id', $userId)
            ->where('visto', 0)
            ->update(['visto' => 1]);

        return view('listpedidoviagem', compact('viagens'));
    }    

/**
 * Lista todas as viagens de determinado usuário para alteração de situação.
 */
public function listAdminViagem($id, Request $request)
{
    /*Valida se é admin*/
    getUserAdminOrRedirect($request);

    $viagens = Viagem::with('pais')
                ->where('user_id', $id)
                ->get();

    return view('listadminviagem', compact('viagens'));
}


}