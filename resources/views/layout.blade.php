<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>@yield('title')</title>
    @stack('head') <!-- Onde o CSS adicional será adicionado -->

    <!-- Adicionando Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header>
        <!-- Cabeçalho do site -->
    </header>
    
    <main>
        @yield('content') <!-- Onde o conteúdo das views será injetado -->
    </main>
    
    <footer>
        <!-- Rodapé do site -->
    </footer>

    @stack('scripts') <!-- Onde os scripts adicionais serão adicionados -->
</body>
</html>
