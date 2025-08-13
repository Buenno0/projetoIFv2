@include('includes.header')
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<body>
    <div class="container_criticas">
        <h1 class="criticas_h1">{{ __('Críticas') }}</h1>
        <a href="{{ url('criticas_user') }}">
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
                                <img src="{{ asset('assets/user_icognite.svg') }}" alt="user" class="user-icon">
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
