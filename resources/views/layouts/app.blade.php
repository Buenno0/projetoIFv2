<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ConectaIF</title>
    
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <div class="wrapper"> @include('partials.sidebar')

        <div class="main">
            <nav class="navbar navbar-expand px-3 border-bottom">
                <button class="toggle-btn" type="button">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </nav>

            <main class="content px-3 py-2">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>