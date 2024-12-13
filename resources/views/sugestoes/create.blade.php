@include('includes.header')

<div class="container">
    <h1>Deixe sua Sugestão</h1>
    <form id="suggestionForm" method="POST" action="{{ route('sugestoes.store') }}">
        @csrf
        <label for="nome">Nome (Opcional)</label>
        <input type="text" id="nome" name="nome" placeholder="Seu nome">
        <div id="nomeError" class="error-message">O nome deve ter no máximo 50 caracteres.</div>

        <label for="email">*E-mail:</label>
        <input type="email" id="email" name="email" >
        <div id="emailError" class="error-message">Por favor, insira um e-mail válido.</div>

        <textarea name="sugestao" id="sugestao" placeholder="Fale sobre sua sugestão"></textarea>
        <div id="sugestaoError" class="error-message">A sugestão não pode estar vazia.</div>

        @error('conteudo')
        <div class="error-message">{{ $message }}</div>
        @enderror

        <button class="add-feedback" type="submit">Adicionar Sugestão</button>
    </form>
</div>

<div id="success-banner" class="hidden">Sugestão enviada com sucesso!</div>
<div id="error-banner" class="hidden">Erro ao enviar sugestão. Tente novamente.</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('suggestionForm');
        const nome = document.getElementById('nome');
        const email = document.getElementById('email');
        const sugestao = document.getElementById('sugestao');
        const nomeError = document.getElementById('nomeError');
        const emailError = document.getElementById('emailError');
        const sugestaoError = document.getElementById('sugestaoError');
        const submitButton = document.querySelector('.add-feedback');
        const successBanner = document.getElementById('success-banner');
        const errorBanner = document.getElementById('error-banner');

        form.addEventListener('submit', function(event) {
            event.preventDefault();
            let valid = true;

            // Reset error states
            nome.classList.remove('error');
            email.classList.remove('error');
            sugestao.classList.remove('error');
            nomeError.style.display = 'none';
            emailError.style.display = 'none';
            sugestaoError.style.display = 'none';

            // Validação do campo nome
            if (nome.value.length > 50) {
                nome.classList.add('error');
                nomeError.style.display = 'block';
                nome.focus();
                valid = false;
            }

            // Validação do campo e-mail
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email.value)) {
                email.classList.add('error');
                emailError.style.display = 'block';
                email.focus();
                valid = false;
            }

            // Validação do campo sugestão
            if (sugestao.value.trim() === '') {
                sugestao.classList.add('error');
                sugestaoError.style.display = 'block';
                sugestao.focus();
                valid = false;
            }

            // Se as validações passarem, envia o formulário via AJAX
            if (valid) {
                submitButton.disabled = true;

                const formData = new FormData(form);

                fetch("{{ route('sugestoes.store') }}", {
                    method: 'POST',
                    body: formData,
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            successBanner.classList.add('visible');
                            setTimeout(() => {
                                successBanner.classList.remove('visible');
                                submitButton.disabled = false;
                                form.reset();
                                window.location.href = '{{ route('obrigado') }}'; // Usando rota do Laravel
                            }, 2000);
                        } else {
                            errorBanner.innerHTML = data.message || 'Erro ao enviar crítica. Tente novamente.';
                            errorBanner.style.display = 'block';
                            setTimeout(() => {
                                errorBanner.style.display = 'none';
                                submitButton.disabled = false;
                            }, 3000);
                        }
                    })
                    .catch(() => {
                        errorBanner.innerHTML = 'Erro ao enviar crítica. Tente novamente.';
                        errorBanner.style.display = 'block';
                        setTimeout(() => {
                            errorBanner.style.display = 'none';
                            submitButton.disabled = false;
                        }, 3000);
                    });
            }
        });
    });

</script>
