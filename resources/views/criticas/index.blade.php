@include('includes.header')

<body>
    <div class="container_criticas">
        <h1 class="criticas_h1">{{ __('Avaliações') }}</h1>
        <a href="{{ url('avaliacoes_user') }}">
            <button class="add-review-button">{{ __('Adicionar crítica') }}</button>
        </a>
        <div class="reviews">
            @if($criticas->isEmpty())
                <p>{{ __('Nenhuma crítica encontrada.') }}</p>
            @else
                @foreach ($criticas as $critica)
                    <div class="review">
                        <div class="review-header">
                            <div class="user_name">
                            </div>
                            <span class="date">{{ $critica->created_at->format('d/m/Y') }}</span>
                        </div>
                        <p class="texto">{{ $critica->conteudo }}</p>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</body>
</html>
