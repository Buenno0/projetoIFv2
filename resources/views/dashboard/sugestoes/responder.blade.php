@extends('layouts.admin')

@section('title', 'Responder Sugestão #' . $sugestao->id)

@section('content')
<div class="dashboard-card animated-fade-in">
    
    <div class="dashboard-header">
        <div class="header-title">
            <i class="fa-solid fa-reply-all"></i> 
            Responder Sugestão <span style="color: #64748b; font-weight: 400;">#{{ $sugestao->id }}</span>
        </div>
        
        <div class="header-controls">
            <a href="{{ route('dashboard.sugestoes') }}" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    <div class="response-container">
        
        <div class="original-message-box">
            <h3 class="section-label">Detalhes da Sugestão</h3>
            
            <div class="user-profile-header">
                <div class="avatar-circle">
                    {{ strtoupper(substr($sugestao->nome ?? 'A', 0, 1)) }}
                </div>
                <div>
                    <div class="user-name">{{ $sugestao->nome ?? 'Anônimo' }}</div>
                    <div class="meta-info">
                        <i class="fa-regular fa-calendar"></i> {{ $sugestao->created_at->format('d/m/Y  H:i') }}
                        @if($sugestao->email)
                            <span class="dot-separator">•</span> {{ $sugestao->email }}
                        @endif
                    </div>
                </div>
            </div>

            <div class="message-content">
                <i class="fa-solid fa-quote-left quote-icon"></i>
                {{ $sugestao->conteudo }}
            </div>

            <div class="status-display">
                <label>Status Atual:</label>
                @if($sugestao->respondido)
                    <span class="badge badge-success"><i class="fa-solid fa-check"></i> Respondido</span>
                @else
                    <span class="badge badge-warning"><i class="fa-regular fa-clock"></i> Pendente de Resposta</span>
                @endif
            </div>
        </div>

        <div class="response-form-box">
            <h3 class="section-label">Sua Resposta</h3>

            @if($sugestao->respondido)
                <div class="alert-box info">
                    <i class="fa-solid fa-circle-info"></i>
                    Esta sugestão já foi respondida em {{ $sugestao->data_resposta ? $sugestao->data_resposta->format('d/m/Y H:i') : 'Data não registrada' }}.
                </div>
                
                <div class="previous-response">
                    <label>Resposta enviada:</label>
                    <div class="response-text">
                        {!! nl2br(e($sugestao->conteudo_resposta)) !!}
                    </div>

                    <div style="margin-top: 15px; border-top: 1px solid #e2e8f0; padding-top: 10px; font-size: 0.85rem; color: #64748b;">
                        <i class="fa-solid fa-user-pen"></i> Respondido por: <strong>{{ $sugestao->respondido_por ?? 'Sistema' }}</strong>
                    </div>
                </div>
            @else
                <form action="{{ route('sugestoes.update', $sugestao->id) }}" method="POST" id="form-responder">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="resposta">Escreva a resposta abaixo:</label>
                        <textarea 
                            name="resposta" 
                            id="resposta" 
                            rows="8" 
                            class="form-control" 
                            placeholder="Olá {{ $sugestao->nome ?? 'estudante' }}, agradecemos sua sugestão..."
                            required></textarea>
                    </div>

                    @if($sugestao->email)
                    <div class="form-check">
                        <input type="checkbox" id="enviar_email" name="enviar_email" checked>
                        <label for="enviar_email">Notificar o usuário por e-mail</label>
                    </div>
                    @endif

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fa-solid fa-paper-plane"></i> Enviar Resposta
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection