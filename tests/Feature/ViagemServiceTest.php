<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Pais;
use App\Models\Viagem; // Importando o modelo Viagem
use Tests\TestCase;

class ViagemServiceTest extends TestCase
{
    /** @test */
    public function usuario_pode_criar_uma_viagem_com_dados_validos()
    {
        // Cria um país para associar à viagem
        $pais = Pais::create([
            'nome' => 'França',
        ]);

        // Verifica se o usuário com ID 1 existe no banco de dados
        $user = User::find(1);

        // Se o usuário não existir, cria um usuário com ID 1 (apenas para fins de teste)
        if (!$user) {
            $user = User::create([
                'name' => 'Usuário Teste',
                'email' => 'teste@exemplo.com',
                'password' => bcrypt('senhaForte123'),
                'perfil' => 1,
            ]);
        }

        // Verifica se o usuário existe no banco de dados
        $this->assertNotNull($user, 'Usuário com ID 1 não encontrado');

        // Autentica o usuário para a requisição
        $this->actingAs($user);

        // Simula a requisição POST para criar a viagem
        $response = $this->postJson(route('api.viagens.create'), [
            'user_id' => $user->id,  // Utilizando o ID do usuário encontrado
            'pais_id' => $pais->id,
            'data_ida' => '2025-05-01',
            'data_volta' => '2025-05-10',
        ]);

        // Verifica se a resposta foi OK (201 Created)
        $response->assertCreated();

        // Verifica se a resposta contém os dados corretos da viagem
        $response->assertJson([
            'status' => true,
            'message' => 'Viagem registrada com sucesso!',
            'viagem' => [
                'user_id' => $user->id,  // Verifica o ID do usuário
                'pais_id' => $pais->id,
                'data_ida' => '2025-05-01',
                'data_volta' => '2025-05-10',
                'visto' => true,  // Verifica se o campo 'visto' foi adicionado corretamente
            ],
        ]);

        // Verifica se a viagem foi salva no banco de dados
        $this->assertDatabaseHas('viagens', [
            'user_id' => $user->id,
            'pais_id' => $pais->id,
            'data_ida' => '2025-05-01',
            'data_volta' => '2025-05-10',
            'visto' => true,
        ]);
    }

     /** @test */
     public function usuario_admin_pode_alterar_situacao_da_viagem()
     {
         // Cria um usuário admin para a autenticação
         $admin = User::create([
             'name' => 'Admin Teste',
             'email' => 'admin@teste.com',
             'password' => bcrypt('adminSenha123'),
             'perfil' => 1,  // Supondo que o perfil 1 seja de admin
         ]);
 
         // Cria um país para associar à viagem
         $pais = Pais::create([
             'nome' => 'França',
         ]);
 
         // Cria uma viagem para alterar a situação
         $viagem = Viagem::create([
             'user_id' => $admin->id,
             'pais_id' => $pais->id,
             'data_ida' => '2025-05-01',
             'data_volta' => '2025-05-10',
             'situacao' => 1, // Supondo que a situação 1 seja "Solicitado"
             'visto' => true,
         ]);
 
         // Autentica o usuário admin
         $this->actingAs($admin);
 
         // Simula a requisição POST para editar a situação da viagem
         $response = $this->postJson(route('api.viagens.edit'), [
             'viagem_id' => $viagem->id,   // ID da viagem a ser alterada
             'situacao' => 2,  // Novo valor para situação (por exemplo, 2 = "Aprovado")
         ]);
 
         // Verifica se a resposta foi OK (200)
         $response->assertStatus(200);
 
         // Verifica se a resposta contém a mensagem esperada
         $response->assertJson([
             'status' => true,
             'message' => 'Situação da viagem alterada com sucesso!',
         ]);
 
         // Verifica se a situação da viagem foi atualizada no banco de dados
         $this->assertDatabaseHas('viagens', [
             'id' => $viagem->id,
             'situacao' => 2, // Verifica se a situação foi alterada para 2
             'visto' => 0,    // O campo 'visto' também deve ser alterado para 0
         ]);
     }
}
