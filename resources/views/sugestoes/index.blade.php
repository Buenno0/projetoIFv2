@include('includes.header')
<body>
    <div class="container_criticas">
        <h1 class="criticas_h1">{{ __('Sugestões') }}</h1>
        <a href="{{ url('sugestoes_user') }}">
            <button class="add-review-button">{{ __('Adicionar sugestão') }}</button>
        </a>
        <div class="reviews">
            @if($sugestoes->isEmpty())
                <p>{{ __('Nenhuma sugestão encontrada.') }}</p>
            @else
                @foreach ($sugestoes as $sugestao)
                    <div class="review">
                        <div class="review-header">
                            <div class="user_name">
                                <img src="{{ asset('assets/user_icognite.svg') }}" alt="user" class="user-icon">
                                <span class="nome_span">
                                    @if($sugestao && $sugestao->nome)
                                        {{ $sugestao->nome }}
                                    @else
                                        Anônimo
                                    @endif
                                </span>
                            </div>
                            <span class="date">{{ $sugestao->created_at->format('d/m/Y') }}</span>
                        </div>
                        <p class="texto">{{ $sugestao->conteudo }}</p>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</body>

