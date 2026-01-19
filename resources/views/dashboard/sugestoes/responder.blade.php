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
        
        <div> 
            <div class="original-message-box card-full-height">
                
                {{-- MUDANÇA AQUI: Título e Status no Topo --}}
                <div class="section-header-row">
                    <h3 class="section-label">Detalhes da Sugestão</h3>
                    
                    <div class="status-top">
                        @if($sugestao->respondido)
                            <span class="badge badge-success"><i class="fa-solid fa-check"></i> Respondido</span>
                        @else
                            <span class="badge badge-warning"><i class="fa-regular fa-clock"></i> Pendente</span>
                        @endif
                    </div>
                </div>
                
                <div class="user-profile-header">
                    <div class="avatar-circle">
                        {{ strtoupper(substr($sugestao->nome ?? 'A', 0, 1)) }}
                    </div>
                    <div>
                        <div class="user-name">{{ $sugestao->nome ?? 'Anônimo' }}</div>
                        <div class="meta-info">
                            <i class="fa-regular fa-calendar"></i> {{ $sugestao->created_at->format('d/m/Y H:i') }}
                            @if($sugestao->email)
                                <span class="dot-separator">•</span> {{ $sugestao->email }}
                            @endif
                        </div>
                    </div>
                </div>

                <div class="message-content">
                    <i class="fa-solid fa-quote-left quote-icon"></i>
                    <div style="width: 100%;">{{ $sugestao->conteudo }}</div>
                </div>

            </div>
        </div>

        {{-- ================================================================= --}}
        {{-- CARD DIREITA: RESPOSTA                                            --}}
        {{-- ================================================================= --}}
        <div>
            @if($sugestao->respondido)
                {{-- MODO VISUALIZAÇÃO (Já Respondido) --}}
                <div class="original-message-box card-full-height admin-theme">
                    {{-- Cabeçalho Simples para manter simetria de altura --}}
                    <div class="section-header-row">
                        <h3 class="section-label" style="color: var(--primary-color);">Resposta</h3>
                    </div>

                    <div class="user-profile-header">
                        <div class="avatar-circle">
                            <i class="fa-solid fa-user-shield"></i>
                        </div>
                        <div>
                            <div class="user-name">{{ $sugestao->respondido_por ?? 'Equipe de Suporte' }}</div>
                            <div class="meta-info">
                                <span style="color: var(--primary-color); font-weight: 600;">Staff</span>
                                <span class="dot-separator">•</span>
                                <i class="fa-regular fa-calendar-check"></i> 
                                {{ $sugestao->data_resposta ? $sugestao->data_resposta->format('d/m/Y H:i') : '' }}
                            </div>
                        </div>
                    </div>

                    <div class="message-content">
                        <div style="width: 4px; border-radius: 4px; margin-right: 10px; flex-shrink:0;"></div>
                        <div style="width: 100%;">
                            {!! nl2br(e($sugestao->conteudo_resposta)) !!}
                        </div>
                    </div>
                </div>

            @else
                {{-- MODO EDIÇÃO (Formulário) --}}
                <div class="original-message-box card-full-height" style="border-color: #cbd5e1;">
                    <div class="section-header-row">
                        <h3 class="section-label">Escrever Resposta</h3>
                    </div>

                    @if ($errors->any())
                        <div class="alert-box error" style="background: #fee2e2; color: #991b1b; padding: 10px; border-radius: 8px; margin-bottom: 15px;">
                            <ul style="margin: 0; padding-left: 20px;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('sugestoes.update', $sugestao->id) }}" method="POST" id="form-responder">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <textarea 
                                name="resposta" 
                                id="resposta" 
                                rows="8" 
                                class="form-control {{ $errors->has('resposta') ? 'is-invalid' : '' }}" 
                                placeholder="Digite sua resposta aqui..."
                                style="min-height: 150px; resize: none;"
                                required
                                minlength="10"></textarea>
                            
                            <div class="invalid-feedback" id="resposta-error" style="display:none; color: #dc2626; margin-top: 5px;">
                                Mínimo de 10 caracteres.
                            </div>
                        </div>

                        <div class="visibility-notice" style="margin-top: 15px;">
                            <div class="notice-icon"><i class="fa-solid fa-eye"></i></div>
                            <div class="notice-text">
                                A resposta será pública para o aluno.
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary" id="btn-submit">
                                <i class="fa-solid fa-paper-plane"></i> Enviar
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function showToast(message, type = 'success') {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        const iconClass = type === 'success' ? 'fa-circle-check' : 'fa-circle-xmark';
        toast.innerHTML = `<i class="fa-solid ${iconClass}" style="font-size: 1.2rem;"></i><div class="toast-content">${message}</div>`;
        container.appendChild(toast);
        requestAnimationFrame(() => { toast.classList.add('show'); });
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 400);
        }, 4000);
    }

    document.addEventListener('DOMContentLoaded', () => {
        @if(session('success')) showToast("{{ session('success') }}", 'success'); @endif

        const form = document.getElementById('form-responder');
        const textarea = document.getElementById('resposta');
        const errorMsg = document.getElementById('resposta-error');
        
        if(form) {
            textarea.addEventListener('input', function() {
                if(this.value.length >= 10) {
                    this.classList.remove('is-invalid');
                    errorMsg.style.display = 'none';
                }
            });

            form.addEventListener('submit', function(e) {
                let isValid = true;
                const valor = textarea.value.trim();
                if(valor.length < 10) {
                    e.preventDefault();
                    textarea.classList.add('is-invalid');
                    errorMsg.style.display = 'block';
                    errorMsg.innerText = 'A resposta deve ter pelo menos 10 caracteres.';
                    showToast('Corrija os erros antes de enviar.', 'error');
                } else {
                    const btn = document.getElementById('btn-submit');
                    btn.disabled = true;
                    btn.querySelector('.btn-text').style.display = 'none'; // Se houver span com essa classe
                    // Adicionei verificação simples caso o botão não tenha a estrutura de spans exata do exemplo anterior
                    if(btn.querySelector('.btn-loader')) btn.querySelector('.btn-loader').style.display = 'inline-block';
                }
            });
        }
    });
</script>
@endpush
