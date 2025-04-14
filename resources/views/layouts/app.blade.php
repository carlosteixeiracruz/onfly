<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- Token CSRF --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Meu Sistema</title>

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">

    {{-- Estilos --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles') {{-- Permite empilhar estilos específicos em views --}}
</head>
<body class="bg-gray-100 text-gray-900 font-['Roboto']">
    
    {{-- Conteúdo da view --}}
    @yield('content')

    {{-- Scripts --}}
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    {{-- Configura CSRF no Axios --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        });
    </script>

    @stack('scripts') {{-- Para scripts específicos por página --}}
</body>
</html>
