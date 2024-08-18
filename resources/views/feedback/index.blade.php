@include('includes.header')

<body>
    <div class="container_criticas">
        <h1 class="criticas_h1">Avaliações</h1>
        <a href="{{ url('avaliacoes_user') }}">
            <button class="add-review-button">Adicionar avaliação</button>
        </a>
        <div class="reviews">
            @if($feedbacks->isEmpty())
                <p>Nenhuma avaliação encontrada.</p>
            @else
                @foreach ($feedbacks as $feedback)
                    @php
                        $icon = '';
                        switch ($feedback->feedback) {
                            case 'bom':
                                $icon = 'good.svg';
                                break;
                            case 'médio':
                                $icon = 'neutro.svg';
                                break;
                            case 'ruim':
                                $icon = 'bad.svg';
                                break;
                        }
                    @endphp

                    <div class="review">
                        <div class="review-header">
                            <div class="user_name">
                                <img src="{{ asset('assets/' . $icon) }}" alt="{{ $feedback->feedback }}" class="user-icon">
                                <span class="nome_span">{{ $feedback->nome }}</span>
                            </div>
                            <span class="date">{{ $feedback->created_at->format('d/m/Y') }}</span>
                        </div>
                        <p class="texto">{{ $feedback->conteudo }}</p>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</body>
</html>
