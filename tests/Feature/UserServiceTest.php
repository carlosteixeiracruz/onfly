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

        /** @test */
    public function usuario_pode_realizar_login_com_dados_validos()
    {
        // Cria um usuário para testar o login
        $user = User::create([
            'name' => 'João Silva',
            'email' => 'joao.silva@gmail.com',
            'password' => bcrypt('SenhaForte#123'), // A senha deve ser criptografada
            'perfil' => 1,
        ]);

        // Simula a requisição POST para fazer o login
        $response = $this->postJson(route('api/users/login'), [
            'email' => 'joao.silva@gmail.com',
            'password' => 'SenhaForte#123',
        ]);

        // Verifica se a resposta foi OK
        $response->assertOk();

        // Verifica se a resposta contém o token de autenticação
        $response->assertJsonStructure([
            'status',
            'message',
            'token',
            'user' => [
                'id',
                'name',
                'email',
                'perfil',
            ],
            'redirect_url',
        ]);

        // Verifica se o token foi gerado e a sessão foi configurada
        $this->assertTrue(Auth::check()); // Verifica se o usuário está autenticado
        $this->assertEquals($user->id, session('userid')); // Verifica se o ID do usuário foi salvo na sessão
    }

    }