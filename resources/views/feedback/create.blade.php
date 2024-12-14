@include('includes.header')

<body>
    <div class="container">
        <h1>Deixe sua Avaliação</h1>
        <form id="feedbackForm" action="{{ route('feedback.emoji') }}" method="post">
            @csrf

            <label for="nome">Nome (opcional):</label>
            <input type="text" id="nome" name="nome" maxlength="50" value="{{ old('nome') }}">
            <div id="nomeError" class="error-message" style="display: none;">O nome deve ter no máximo 50 caracteres.</div>

            <textarea name="avaliacao" id="avaliacao" placeholder="Conte como foi sua experiência">{{ old('avaliacao') }}</textarea>
            <div id="avaliacaoError" class="error-message" style="display: none;">A avaliação não pode estar vazia.</div>

            <button class="add-feedback" type="submit">Próximo</button>
        </form>
    </div>   

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('feedbackForm');
            const nome = document.getElementById('nome');
            const avaliacao = document.getElementById('avaliacao');
            const nomeError = document.getElementById('nomeError');
            const avaliacaoError = document.getElementById('avaliacaoError');

            form.addEventListener('submit', function(event) {
                let valid = true;

                // Resetando os estados de erro
                nome.classList.remove('error');
                avaliacao.classList.remove('error');
                nomeError.style.display = 'none';
                avaliacaoError.style.display = 'none';

                // Validação do campo nome
                if (nome.value.length > 50) {
                    nome.classList.add('error');
                    nomeError.style.display = 'block';
                    nome.focus();
                    valid = false;
                }

                // Validação do campo avaliação
                if (avaliacao.value.trim() === '') {
                    avaliacao.classList.add('error');
                    avaliacaoError.style.display = 'block';
                    avaliacao.focus();
                    valid = false;
                }

                // Se as validações falharem, previne o envio do formulário
                if (!valid) {
                    event.preventDefault();
                }
            });
        });
    </script>
</body>
