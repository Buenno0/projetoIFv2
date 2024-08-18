<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Instituto Federal' }}</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</head>
<body>
    <header>
        <a href="#" onclick="goBack()">
            <img class="back-button" src="{{ asset('assets/back-button.svg') }}" alt="Voltar">
        </a>
        <img src="{{ asset('assets/ifsp_logo_itp.png') }}" alt="Instituto Federal" class="logo">
    </header>
</body>
</html>
