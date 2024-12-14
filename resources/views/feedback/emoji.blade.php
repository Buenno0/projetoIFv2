@include('includes.header')

<body>
    <div class="container">
        <h1>Deixe sua avaliação</h1>
        <div class="buttons">
            <button class="btn" id="ruim">
                <img class="icon" src="{{ asset('assets/bad.svg') }}" alt="ruim">
                Ruim
            </button>
            <button class="btn" id="medio">
                <img class="icon" src="{{ asset('assets/neutro.svg') }}" alt="medio">
                Médio
            </button>
            <button class="btn" id="bom">
                <img class="icon" src="{{ asset('assets/good.svg') }}" alt="bom">
                Bom
            </button>
        </div>
        <button class="add-feedback">Adicionar avaliação</button>
    </div>

    <div id="success-banner" class="hidden">Feedback enviado com sucesso!</div>
    <div id="error-banner" class="hidden">Por favor, selecione uma das opções.</div>
    <div id="duplicate-banner" class="hidden">Feedback já enviado anteriormente.</div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const buttons = document.querySelectorAll('.btn');
        let selectedFeedback = '';
        const successBanner = document.getElementById('success-banner');
        const errorBanner = document.getElementById('error-banner');
        const duplicateBanner = document.getElementById('duplicate-banner');
        const addFeedbackButton = document.querySelector('.add-feedback');

        buttons.forEach(button => {
            button.addEventListener('click', () => {
                buttons.forEach(btn => btn.classList.remove('selected'));
                button.classList.add('selected');
                selectedFeedback = button.id;
                console.log('Feedback selecionado:', selectedFeedback);  // Log para depuração
            });
        });

        addFeedbackButton.addEventListener('click', () => {
            console.log('Feedback a ser enviado:', selectedFeedback);  // Log para depuração
            if (selectedFeedback) {
                addFeedbackButton.classList.add('disabled');

                // Cria um objeto FormData com os dados
                const formData = new FormData();
                formData.append('feedback', selectedFeedback);  // Adiciona o feedback selecionado
                formData.append('nome', '');  // Se for um campo de nome opcional, pode ser vazio
                formData.append('avaliacao', '');  // Aqui você pode adicionar a avaliação se desejar

                fetch("{{ route('feedback.submitExperience') }}", {  // Rota do Laravel para processar a avaliação
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'  // Token CSRF para proteger a requisição
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        successBanner.classList.add('visible');
                        localStorage.setItem('feedbackSubmitted', 'true');
                        setTimeout(() => {
                            successBanner.classList.remove('visible');
                            window.location.href = "{{ route('obrigado') }}";  // Redirecionamento após sucesso
                        }, 3000);
                    } else if (data.message === 'Feedback já enviado anteriormente.') {
                        duplicateBanner.classList.add('visible');
                        setTimeout(() => {
                            duplicateBanner.classList.remove('visible');
                            addFeedbackButton.classList.remove('disabled');
                        }, 3000);
                    } else {
                        errorBanner.classList.add('visible');
                        setTimeout(() => {
                            errorBanner.classList.remove('visible');
                            addFeedbackButton.classList.remove('disabled');
                        }, 3000);
                    }
                })
                .catch(() => {
                    errorBanner.classList.add('visible');
                    setTimeout(() => {
                        errorBanner.classList.remove('visible');
                        addFeedbackButton.classList.remove('disabled');
                    }, 3000);
                });
            } else {
                errorBanner.classList.add('visible');
                setTimeout(() => {
                    errorBanner.classList.remove('visible');
                }, 1300);
            }
        });
    });
    </script>
</body>
