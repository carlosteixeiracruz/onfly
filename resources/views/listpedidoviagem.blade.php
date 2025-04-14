@extends('layouts.app')

@section('content')
<style>
    /* Classes para o status das viagens */
    .status-solicitado {
        background-color: #f3f4f6 !important;
    }

    .status-aprovado {
        background-color: #d1fae5 !important;
    }

    .status-cancelado {
        background-color: #fee2e2 !important;
    }
</style>

<div class="max-w-7xl mx-auto py-8">
    {{-- Link Voltar --}}
    <div class="mb-6">
        <a href="{{ url('/painel') }}" class="inline-block bg-indigo-600 text-white px-4 py-2 rounded-md shadow hover:bg-indigo-700 transition">
            ← Voltar ao Painel
        </a>
    </div>
    <h2 class="text-2xl font-bold mb-6">Pedidos de Viagem</h2>

    <table id="tabelaViagens" class="display w-full">
        <thead>
            <tr>
                <th style="display:none;">Visto</th> {{-- Coluna oculta para ordenação --}}
                <th>ID</th>
                <th>País</th>
                <th>Data Ida</th>
                <th>Data Volta</th>
                <th>Situação</th>
                <th>Data da Solicitação</th>
            </tr>
        </thead>
        <tbody>
            @foreach($viagens as $viagem)
                @php
                    $situacoes = [1 => 'Solicitado', 2 => 'Aprovado', 3 => 'Cancelado'];
                    $classeStatus = match($viagem->situacao) {
                        1 => 'status-solicitado',
                        2 => 'status-aprovado',
                        3 => 'status-cancelado',
                        default => ''
                    };
                @endphp
                <tr>
                    <td style="display:none;">{{ $viagem->visto }}</td>
                    <td>{{ $viagem->id }}</td>
                    <td>{{ $viagem->pais->nome }}</td>
                    <td>{{ \Carbon\Carbon::parse($viagem->data_ida)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($viagem->data_volta)->format('d/m/Y') }}</td>
                    <td class="status-coluna">{{ $situacoes[$viagem->situacao] ?? 'Desconhecida' }}</td>
                    <td>{{ $viagem->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- DataTables --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        var table = $('#tabelaViagens').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
            },
            responsive: true,
            pageLength: 10,
            order: [[0, 'asc']], // Ordena pela coluna "visto"
            columnDefs: [
                { targets: 0, visible: false } // Oculta a coluna "visto"
            ],
            drawCallback: function(settings) {
                $('#tabelaViagens tbody tr').each(function() {
                    var $row = $(this);
                    var status = $row.find('td.status-coluna').text().trim();

                    $row.removeClass('status-solicitado status-aprovado status-cancelado');

                    if (status === 'Solicitado') {
                        $row.addClass('status-solicitado');
                    } else if (status === 'Aprovado') {
                        $row.addClass('status-aprovado');
                    } else if (status === 'Cancelado') {
                        $row.addClass('status-cancelado');
                    }
                });
            }
        });
    });
</script>
@endsection
