@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-10 rounded-2xl shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Criar Conta</h2>

        <form id="registerForm" class="space-y-5">
            @csrf

            {{-- Nome --}}
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nome</label>
                <input type="text" id="name" name="name" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>

            {{-- E-mail --}}
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">E-mail</label>
                <input type="email" id="email" name="email" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>

            {{-- Senha --}}
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Senha</label>
                <input type="password" id="password" name="password" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>

            {{-- Confirmação da Senha --}}
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar
                    Senha</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>

            {{-- Botão de Cadastro --}}
            <div>
                <button type="submit"
                    class="w-full bg-indigo-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-indigo-700 transition">
                    Criar Conta
                </button>
            </div>
        </form>

        <div id="feedback" class="mt-4 text-center text-sm text-gray-700"></div>

        <div class="text-center mt-4 text-sm text-gray-600">
            Já tem uma conta?
            <a href="{{ route('login.form') }}" class="text-indigo-600 hover:underline">Entrar</a>
        </div>
    </div>
</div>

<script>
document.getElementById('registerForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const password_confirmation = document.getElementById('password_confirmation').value;

    const feedback = document.getElementById('feedback');
    feedback.innerText = 'Enviando...';

    try {
        const response = await fetch('/api/users/insert', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                name,
                email,
                password,
                password_confirmation
            })
        });

        const data = await response.json();

        if (response.ok) {
            window.location.href = data.redirect_url;
        } else {
            feedback.innerText = data.message || 'Erro ao criar conta.';
            feedback.classList.remove('text-green-600');
            feedback.classList.add('text-red-500');
        }
    } catch (error) {
        feedback.innerText = 'Erro de conexão com a API.';
        feedback.classList.remove('text-green-600');
        feedback.classList.add('text-red-500');
    }
});
</script>

@endsection