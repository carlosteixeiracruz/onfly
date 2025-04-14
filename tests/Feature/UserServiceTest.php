<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    /** @test */
    public function usuario_pode_ser_cadastrado_com_dados_validos()
    {
        // Simula a requisição POST para cadastrar o usuário
        $response = $this->postJson(route('api/users/insert'), [
            'name' => 'João Silva',
            'email' => 'joao.silva@gmail.com',
            'password' => 'SenhaForte#123',
            "perfil"  => 1
        ]);        

        // Verifica se a resposta foi OK
        $response->assertOk();
    }
}
