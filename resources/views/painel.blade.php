@extends('layouts.app')

@section('content')
{{-- Topbar limpa --}}
<nav class="bg-gray-800 text-white shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-end h-16 items-center">
            @auth
            <span class="hidden sm:inline text-sm mr-4">Olá, {{ Auth::user()->name }}</span>
            <form action=" " method="POST">
                @csrf
                <button type="submit" class="text-sm bg-red-500 px-3 py-1 rounded hover:bg-red-600">Sair</button>
            </form>
            @endauth
        </div>
    </div>
</nav>

{{-- Área dos widgets --}}
<div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

        {{-- Link para Pedidos de Viagem --}}

        <h2 class="text-3xl font-bold mb-2 drop-shadow">Pedidos de Viagem</h2>
        <a href="{{ url('/viagem/pedido') }}"
            class="bg-indigo-600 text-white rounded-2xl shadow-lg hover:shadow-xl transition transform hover:scale-105 p-8 text-center">
            <p class="text-sm text-indigo-100">Efetuar pedidos</p>
        </a>
        <a href="{{ url('/viagem/list') }}"
            class="bg-indigo-600 text-white rounded-2xl shadow-lg hover:shadow-xl transition transform hover:scale-105 p-8 text-center">
            <p class="text-sm text-indigo-100">Visualizar pedidos</p>
        </a>

        {{-- Link para Admin --}}
        @if(Auth::check() && Auth::user()->perfil == 2)
        <h2 class="text-3xl font-bold mb-2 drop-shadow">Admin</h2>
        <a href="{{ url('/users/list') }}"
            class="bg-emerald-600 text-white rounded-2xl shadow-lg hover:shadow-xl transition transform hover:scale-105 p-8 text-center">
            <p class="text-sm text-emerald-100">Aprovar pedidos de viagens</p>
        </a>
        @endif


    </div>
</div>

{{-- Notificação flutuante --}}
@if($existeAtualizacao)
<div id="notificacao"
     style="position: fixed; bottom: 20px; right: 20px; background-color: #fde68a; color: #92400e; font-weight: bold; padding: 1rem; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); max-width: 300px; z-index: 9999; transition: all 0.3s ease;">
    <div style="display: flex; justify-content: space-between; align-items: start;">
        <div style="padding-right: 0.75rem;">
            <p>📢 Existe atualizações nas suas solicitações.</p>
            <a href="{{ url('/viagem/list') }}" style="font-size: 0.875rem; text-decoration: underline; color: #78350f;">Clique aqui para vê-las</a>
        </div>
        <button onclick="document.getElementById('notificacao').remove()"
                style="background: none; border: none; color: #92400e; font-size: 1.25rem; font-weight: bold; cursor: pointer; line-height: 1;">×</button>
    </div>
</div>
@endif


<script>
    // Mostra a notificação flutuante ao carregar a página
    window.addEventListener('DOMContentLoaded', () => {
        const noti = document.getElementById('notificacao');
        if (noti) {
            noti.style.display = 'block';
        }
    });
</script>
@endsection