@extends('layouts.app')

@section('content')
<style>
#modalPerfil {
    display: none; /* Modal começa oculta */
    position: fixed; 
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* Fundo semi-transparente */
    z-index: 50;
    justify-content: center;
    align-items: center;
}

#modalPerfil.flex {
    display: flex; /* Exibe a modal */
}

#modalPerfil > div {
    background-color: white;
    border-radius: 8px;
    padding: 20px;
    max-width: 500px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

</style>

<div class="max-w-7xl mx-auto py-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Link Voltar --}}
    <div class="mb-6">
        <a href="{{ url('/painel') }}" class="inline-block bg-indigo-600 text-white px-4 py-2 rounded-md shadow hover:bg-indigo-700 transition">
            ← Voltar ao Painel
        </a>
    </div>

    <h2 class="text-2xl font-bold mb-6">Usuários</h2>

    <table id="tabelaUsuarios" class="display w-full">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Perfil</th>
                <th>Data Criação</th>
                <th>Painel</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @php
                            $perfis = [1 => 'Usuário Comum', 2 => 'Administrador'];
                        @endphp
                        {{ $perfis[$user->perfil] ?? 'Desconhecido' }}
                    </td>
                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                    <td class="text-center">
                        <div class="flex gap-4 items-center justify-center">
                            <a 
                                href="{{ url('/viagem/listadmin/' . $user->id) }}" 
                                class="text-blue-600 hover:text-blue-800 text-2xl"
                                title="Ver Viagens"
                            >
                                ✈️
                            </a>

                            <button 
                                type="button"
                                onclick="abrirModalPerfil({{ $user->id }})" 
                                class="text-green-600 hover:text-green-800 text-2xl"
                                title="Editar Perfil"
                            >
                                📝
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- Modal de Alterar Perfil --}}
<div id="modalPerfil" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center">
    <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-lg">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Alterar Perfil</h3>
        
        <p id="nomeUsuario" class="mb-6 text-gray-700"></p>
        <p id="emailUsuario" class="mb-6 text-gray-700"></p>
        
        <input type="hidden" id="userId" value="">

        <div class="flex justify-end gap-4">
            <button type="button" onclick="fecharModalPerfil()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Fechar</button>
            <button type="button" onclick="confirmarAlteracaoPerfil()" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Salvar</button>
        </div>
    </div>
</div>

{{-- Estilos e scripts do DataTables --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    function confirmarAlteracaoPerfil() {
        const id = document.getElementById('userId').value;
        // Aqui você pode adicionar a lógica para atualizar o perfil do usuário, se necessário
        alert("Alterações salvas para o usuário: " + id);
        fecharModalPerfil();
    }

    $(document).ready(function() {
        $('#tabelaUsuarios').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
            },
            responsive: true,
            pageLength: 10
        });
    });

    function abrirModalPerfil(userId) {
    console.log('ID do Usuário:', userId);  // Verifique se o ID do usuário é correto
    const users = @json($users);
    const usuario = users.find(u => u.id === userId);

    if (usuario) {
        console.log('Usuário encontrado:', usuario);  // Verifique se o usuário está sendo encontrado corretamente
        document.getElementById('userId').value = usuario.id;
        document.getElementById('nomeUsuario').innerText = "Nome: " + usuario.name;
        document.getElementById('emailUsuario').innerText = "Email: " + usuario.email;

        let modal = document.getElementById('modalPerfil');
        if (modal) {
            modal.classList.remove('hidden');  // Remove a classe 'hidden' para exibir
            modal.classList.add('flex');       // Adiciona a classe 'flex' para usar o flexbox
        }
    } else {
        console.log('Usuário não encontrado.');
    }
}

function fecharModalPerfil() {
    let modal = document.getElementById('modalPerfil');
    if (modal) {
        modal.classList.add('hidden');    // Adiciona a classe 'hidden' para esconder a modal
        modal.classList.remove('flex');  // Remove a classe 'flex'
    }
}

</script>
@endsection
