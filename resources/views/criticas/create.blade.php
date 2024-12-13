@include('includes.header')

<div class="container">
    <h1>Deixe sua crítica</h1>
    <form id="suggestionForm" method="POST" action="{{ route('criticas.store') }}">
        @csrf
        <textarea name="conteudo" id="critica" placeholder="Explique sua crítica..."></textarea>
        @error('conteudo')
        <div class="error-message">{{ $message }}</div>
        @enderror
        <button class="add-feedback" type="submit">Adicionar crítica</button>
    </form>
</div>

<div id="success-banner" class="hidden">Crítica enviada com sucesso!</div>
<div id="error-banner" class="hidden">Erro ao enviar crítica. Tente novamente.</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('suggestionForm');
        const critica = document.getElementById('critica');
        const submitButton = document.querySelector('.add-feedback');
        const successBanner = document.getElementById('success-banner');
        const errorBanner = document.getElementById('error-banner');

        form.addEventListener('submit', function(event) {
            event.preventDefault();
            let valid = true;

            // Reset error states
            critica.classList.remove('error');
            errorBanner.style.display = 'none';

            // Validação do campo crítica
            if (critica.value.trim() === '') {
                critica.classList.add('error');
                errorBanner.innerHTML = 'A crítica não pode estar vazia.';
                errorBanner.style.display = 'block';
                critica.focus();
                valid = false;
            }

            // Se as validações passarem, envia o formulário via AJAX
            if (valid) {
                submitButton.disabled = true;

                const formData = new FormData(form);

                fetch("{{ route('criticas.store') }}", {
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
                                window.location.href = '{{ route('obrigado') }}';
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
