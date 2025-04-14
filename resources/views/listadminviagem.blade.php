@extends('layouts.app')

@section('content')
<style>
#modalCancelamento {
    display: none;
    /* Esconde a modal inicialmente */
}

#modalCancelamento {
    display: none;
    /* Modal começa oculta */
    position: fixed;
    /* Posicionamento fixo para garantir que fique na tela inteira */
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    /* Fundo semi-transparente */
    z-index: 50;
    justify-content: center;
    /* Centraliza na tela */
    align-items: center;
    /* Centraliza na tela */
}

#modalCancelamento>div {
    background-color: white;
    /* Fundo branco para o conteúdo da modal */
    border-radius: 8px;
    /* Borda arredondada */
    padding: 20px;
    max-width: 500px;
    /* Limita a largura máxima */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    /* Sombra suave */
}
</style>

<div class="max-w-7xl mx-auto py-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Link Voltar --}}
    <div class="mb-6">
        <a href="{{ url('/users/list') }}"
            class="inline-block bg-indigo-600 text-white px-4 py-2 rounded-md shadow hover:bg-indigo-700 transition">
            ← Voltar a lista de usuários
        </a>
    </div>

    <h2 class="text-2xl font-bold mb-6">Pedidos de Viagem</h2>

    <table id="tabelaViagens" class="display w-full">
        <thead>
            <tr>
                <th>ID</th>
                <th>País</th>
                <th>Data Ida</th>
                <th>Data Volta</th>
                <th>Situação</th>
                <th>Data Criação</th>
                <th>Painel</th>
            </tr>
        </thead>
        <tbody>
            @foreach($viagens as $viagem)
            <tr>
                <td>{{ $viagem->id }}</td>
                <td>{{ $viagem->pais->nome }}</td>
                <td>{{ \Carbon\Carbon::parse($viagem->data_ida)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($viagem->data_volta)->format('d/m/Y') }}</td>
                <td>
                    @php
                    $situacoes = [1 => 'Solicitado', 2 => 'Aprovado', 3 => 'Cancelado'];
                    @endphp
                    {{ $situacoes[$viagem->situacao] ?? 'Desconhecida' }}
                </td>
                <td>{{ $viagem->created_at->format('d/m/Y H:i') }}</td>
                <td>

                    @if(auth()->id() !== $viagem->user_id)
                    <button class="text-red-600 hover:text-red-800 text-xl"
                        onclick="abrirModal({{ $viagem->id }}, {{ $viagem->situacao }})" title="Cancelar">
                        ✈️
                    </button>
                    @else
                    <span
                        class="absolute bottom-0 left-0 hidden text-red-600 font-semibold transition duration-300 transform opacity-0 hover:opacity-100 hover:translate-y-1">
                        ❌ Não é possível alterar a situação das suas próprias solicitações
                    </span>
                    @endif


                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal -->
<div id="modalCancelamento" class="flex items-center justify-center fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-lg">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Editar Situação da Viagem</h3>

        <!-- Bloco que será mostrado só se estiver APROVADO -->
        <div id="blocoEdicao">
            <p class="mb-1 text-sm"><strong>Destino:</strong> <span id="destinoViagem"></span></p>
            <p class="mb-1 text-sm"><strong>Data de Partida:</strong> <span id="partidaViagem"></span></p>
            <p class="mb-4 text-sm"><strong>Data de Retorno:</strong> <span id="retornoViagem"></span></p>

            <label for="situacaoSelect" class="block text-sm font-medium text-gray-700 mb-2">Nova Situação</label>
            <select id="situacaoSelect" class="w-full border-gray-300 rounded p-2"
                onchange="verificarAvisoCancelamento()">
                <!--option value="1">Solicitado</option!-->
                <option value="2">Aprovado</option>
                <option value="3">Cancelado</option>
            </select>
            <br>


            <div id="avisoCancelamento" class="mt-3 text-red-600 text-sm hidden">
                ⚠️ Atenção: esta viagem já foi aprovada. Tem certeza que deseja cancelar?
            </div>
            <br>
        </div>

        <input type="hidden" id="viagemId" value="">
        <input type="hidden" id="situacaoAtual" value="">

        <div class="flex justify-end gap-4 mt-6">
            <button type="button" onclick="fecharModal()"
                class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Fechar</button>
            <button type="button" onclick="confirmarEdicao()"
                class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Salvar</button>
        </div>
    </div>
</div>




{{-- Estilos e scripts do DataTables --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
function editarSituacao(viagemId, situacao) {
    fetch('/viagem/edit', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                viagem_id: viagemId,
                situacao: situacao
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status) {
                alert(data.message);
                location.reload();
            } else {
                alert('Erro ao atualizar situação.');
            }
        })
        .catch(error => {
            console.error('Erro na requisição:', error);
        });
}


function confirmarAlteracaoSituacao() {
    const id = document.getElementById('viagemId').value;
    const novaSituacao = document.getElementById('situacaoSelect').value;

    fetch('/viagem/edit', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                viagem_id: id,
                situacao: novaSituacao
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status) {
                alert(data.message);
                fecharModal(); // Fecha a modal
                location.reload(); // Atualiza a lista
            } else {
                alert('Erro ao atualizar situação.');
            }
        })
        .catch(error => {
            console.error('Erro na requisição:', error);
            alert('Erro na requisição.');
        });
}



$(document).ready(function() {
    $('#tabelaViagens').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
        },
        responsive: true,
        pageLength: 10
    });
});
</script>


<script>
const viagens = @json($viagens); // Carrega os dados no JavaScript

function abrirModal(id, situacaoAtual, destino = '', dataPartida = '', dataRetorno = '') {
    document.getElementById('viagemId').value = id;
    document.getElementById('situacaoAtual').value = situacaoAtual;

    document.getElementById('destinoViagem').innerText = destino;
    document.getElementById('partidaViagem').innerText = dataPartida;
    document.getElementById('retornoViagem').innerText = dataRetorno;

    document.getElementById('situacaoSelect').value = situacaoAtual;

    // Esconde o aviso quando o modal for aberto
    document.getElementById('avisoCancelamento').style.display = 'none'; // Oculta o aviso ao abrir o modal

    // Mostrar ou ocultar o bloco de edição
    const blocoEdicao = document.getElementById('blocoEdicao');
    if (parseInt(situacaoAtual) === 2) {
        blocoEdicao.classList.remove('hidden');
    } else {
        blocoEdicao.classList.add('hidden');
    }

    // Executa verificação inicial do aviso
    verificarAvisoCancelamento();

    document.getElementById('modalCancelamento').style.display = 'flex';
}



function verificarAvisoCancelamento() {
    const situacaoOriginal = parseInt(document.getElementById('situacaoAtual').value); // Situação original
    const novaSituacao = parseInt(document.getElementById('situacaoSelect').value); // Nova situação selecionada
    const aviso = document.getElementById('avisoCancelamento'); // Elemento do aviso

    // Exibe o aviso se a situação original for "Aprovado" (2) e a nova for "Cancelado" (3)
    if (situacaoOriginal === 2 && novaSituacao === 3) {
        aviso.classList.remove('hidden'); // Mostra o aviso
        aviso.style.display = 'block'; // Garante que o aviso será mostrado
    } else {
        aviso.classList.add('hidden'); // Oculta o aviso
        aviso.style.display = 'none'; // Garante que o aviso será oculto
    }
}




function fecharModal() {
    document.getElementById('modalCancelamento').style.display = 'none';
}

function confirmarEdicao() {
    const id = document.getElementById('viagemId').value;
    const situacao = document.getElementById('situacaoSelect').value;
    editarSituacao(id, parseInt(situacao));
}


function formatarData(dataString) {
    const data = new Date(dataString);
    const dia = String(data.getDate()).padStart(2, '0');
    const mes = String(data.getMonth() + 1).padStart(2, '0');
    const ano = data.getFullYear();
    return `${dia}/${mes}/${ano}`;
}
</script>

@endsection