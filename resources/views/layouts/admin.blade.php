<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/menu-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard-theme.css') }}"> </head>
<body>

    @include('dashboard.includes.sidebar')

    <div id="toast-container"></div>

    <main class="main-content">

        <div class="container">
            @yield('content')
        </div>
    </main>

    <script src="{{ asset('js/dashboard-core.js') }}"></script>
    
    @stack('scripts')
</body>
</html>