@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-10 rounded-2xl shadow-md w-full max-w-xl">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Solicitar Viagem</h2>

        <form id="viagemForm" class="space-y-5">
            @csrf

            {{-- Destino (país) --}}
            <div>
                <label for="pais_id" class="block text-sm font-medium text-gray-700">Destino</label>
                <select id="pais_id" name="pais_id" class="select2 w-full" required>
                    <option value="">Selecione o país</option>
                </select>
            </div>

            {{-- Data de Ida --}}
            <div>
                <label for="data_ida" class="block text-sm font-medium text-gray-700">Data de Ida</label>
                <input type="date" id="data_ida" name="data_ida" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>

            {{-- Data de Volta --}}
            <div>
                <label for="data_volta" class="block text-sm font-medium text-gray-700">Data de Volta</label>
                <input type="date" id="data_volta" name="data_volta"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" disabled>
            </div>

            {{-- Botão --}}
            <div>
                <button type="submit"
                        class="w-full bg-indigo-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-indigo-700 transition">
                    Enviar Pedido
                </button>
                <a href="{{ url('/painel') }}" class="bg-indigo-600 text-white rounded-2xl shadow-lg hover:shadow-xl transition transform hover:scale-105 p-8 text-center"><p class="text-sm text-indigo-100">← Voltar ao Painel</p></a>
            </div>
        </form>

        <div id="feedback" class="mt-4 text-center text-sm text-gray-700"></div>
    </div>
</div>

{{-- Select2 --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Carrega países
    fetch('/api/paises')
        .then(response => response.json())
        .then(paises => {
            const select = document.getElementById('pais_id');
            paises.forEach(pais => {
                const option = document.createElement('option');
                option.value = pais.id;
                option.text = pais.nome;
                select.appendChild(option);
            });
            $('.select2').select2({
                placeholder: 'Digite e selecione um país',
                allowClear: true
            });
        });

    const form = document.getElementById('viagemForm');
    const feedback = document.getElementById('feedback');
    const dataIda = document.getElementById('data_ida');
    const dataVolta = document.getElementById('data_volta');

    // Ativa/desativa data de volta
    dataIda.addEventListener('change', function () {
        if (dataIda.value) {
            dataVolta.disabled = false;
            dataVolta.min = dataIda.value;
        } else {
            dataVolta.disabled = true;
            dataVolta.value = '';
        }
    });

    // Submissão do formulário
    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        const ida = new Date(dataIda.value);
        const volta = new Date(dataVolta.value);

        if (dataVolta.value && volta < ida) {
            feedback.innerText = 'A data de volta deve ser igual ou posterior à data de ida.';
            feedback.className = 'mt-4 text-center text-sm text-red-500';
            return;
        }

        const data = {
            user_id: {{ $userId }},
            pais_id: document.getElementById('pais_id').value,
            data_ida: dataIda.value,
            data_volta: dataVolta.value
        };

        //feedback.innerText = 'Enviando...';
        feedback.className = 'mt-4 text-center text-sm text-gray-700';

     
            const response = await fetch('/viagem/salvarpedido', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },

                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (response.ok) {
                alert('Pedido enviado com sucesso!');
                window.location.href = '/viagem/list';
                feedback.className = 'mt-4 text-center text-sm text-green-600';
                form.reset();
                $('.select2').val(null).trigger('change');
                dataVolta.disabled = true;
            } else if (response.status === 422 && result.errors) {
                const errors = Object.values(result.errors).flat().join('\n');
                feedback.innerText = errors;
                feedback.className = 'mt-4 text-center text-sm text-red-500';
            } else {
                feedback.innerText = result.message || 'Erro ao enviar pedido.';
                feedback.className = 'mt-4 text-center text-sm text-red-500';
            }

    });
});
</script>
@endsection
