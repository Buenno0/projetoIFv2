@include('includes.header')

<body>
    <div class="container">
        <h1>Queremos ouvir <span>você!</span></h1>
        <div class="buttons">
            <a href="{{ url('main/criticas') }}">
                <button class="btn">
                    <img class="icon" src="{{ asset('assets/critica.svg') }}" alt="Críticas">
                    Críticas
                </button>
            </a>
            <a href="{{ url('feedback') }}">
                <button class="btn">
                    <img class="icon" src="{{ asset('assets/sugestoes.svg') }}" alt="Sugestões">
                    Sugestões
                </button>
            </a>
            <a href="{{ url('main/contatos') }}">
                <button class="btn">
                    <img class="icon" src="{{ asset('assets/contatos.svg') }}" alt="Contatos">
                    Contatos
                </button>
            </a>
            <a href="{{ url('feedback') }}">
                <button class="btn">
                    <img class="icon" src="{{ asset('assets/avaliacoes.svg') }}" alt="Avaliações">
                    Avaliações
                </button>
            </a>
            <button class="btn" id="denuncia-btn">
                <img class="icon" src="{{ asset('assets/denuncia.svg') }}" alt="Denúncias">
                Denúncias
            </button>
        </div>
    </div>
</body>
</html>
