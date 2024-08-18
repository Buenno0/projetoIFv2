@include('includes.header')

<body>
    <div class="container">
        <h1>Queremos ouvir <span>você!</span></h1>
        <div class="buttons">
            <a href="{{ url('criticas') }}">
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
            <form id="denuncia-form" method="POST" action="{{ url('/save_denuncia') }}">
                @csrf
                <button type="submit" class="btn" id="denuncia-btn">
                    <img class="icon" src="{{ asset('assets/denuncia.svg') }}" alt="Denúncias">
                    Denúncias
                </button>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('denuncia-form').addEventListener('submit', function(event) {
            event.preventDefault(); 
            var form = this;
            
            var xhr = new XMLHttpRequest();
            xhr.open('POST', form.action, true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            xhr.onload = function() {
                if (xhr.status === 200) {
                    window.location.href = 'https://falabr.cgu.gov.br/web/home';
                } else {
                    alert('Houve um erro.');
                }
            };
            
            var formData = new FormData(form);
            var encodedData = [];
            formData.forEach(function(value, key) {
                encodedData.push(encodeURIComponent(key) + "=" + encodeURIComponent(value));
            });
            xhr.send(encodedData.join('&'));
        });
    </script>
</body>
</html>
