<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Painel - Feedback</title>

    <!-- CSS principal -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

    {{-- Inclui o header do Laravel --}}
    @include('includes.header')

    <div class="container">
        <h1>Queremos ouvir <span>você!</span></h1>

        <div class="buttons">
            <!-- Botão Críticas -->
            <a href="{{ url('criticas') }}">
                <button class="btn">
                    <img src="{{ asset('assets/critica.svg') }}" alt="Críticas">
                    Críticas
                </button>
            </a>

            <!-- Botão Sugestões -->
            <a href="{{ url('sugestoes') }}">
                <button class="btn">
                    <img src="{{ asset('assets/sugestoes.svg') }}" alt="Sugestões">
                    Sugestões
                </button>
            </a>

            <!-- Botão Contatos -->
            <a href="{{ url('contatos') }}">
                <button class="btn">
                    <img src="{{ asset('assets/contatos.svg') }}" alt="Contatos">
                    Contatos
                </button>
            </a>

            <!-- Botão Avaliações -->
            <a href="{{ url('feedback') }}">
                <button class="btn">
                    <img src="{{ asset('assets/avaliacoes.svg') }}" alt="Avaliações">
                    Avaliações
                </button>
            </a>

            <!-- Form de denúncia -->
            <form id="denuncia-form" method="POST" action="{{ url('/save_denuncia') }}">
                @csrf
                <button type="submit" class="btn" id="denuncia-btn">
                    <img src="{{ asset('assets/denuncia.svg') }}" alt="Denúncias">
                    Denúncias
                </button>
            </form>
        </div>
    </div>

    <!-- Script para envio e redirecionamento da denúncia -->
    <script>
        document.getElementById('denuncia-form').addEventListener('submit', function(event) {
            event.preventDefault();
            var form = this;

            var xhr = new XMLHttpRequest();
            xhr.open('POST', form.action, true);
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            xhr.onload = function() {
                if (xhr.status === 200) {
                    window.location.href = 'https://falabr.cgu.gov.br/web/home';
                } else {
                    alert('Houve um erro ao enviar sua denúncia.');
                }
            };

            var formData = new FormData(form);
            xhr.send(formData);
        });
    </script>
</body>
</html>
