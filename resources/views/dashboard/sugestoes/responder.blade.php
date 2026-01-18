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
                        <i class="fa-regular fa-calendar"></i> {{ $sugestao->created_at->format('d/m/Y H:i') }}
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
                    <i class="fa-solid fa-circle-check"></i>
                    Esta sugestão foi respondida em {{ $sugestao->data_resposta ? $sugestao->data_resposta->format('d/m/Y H:i') : '' }}.
                </div>
                
                <div class="previous-response">
                    <label>Conteúdo da Resposta:</label>
                    <div class="response-text">
                        {!! nl2br(e($sugestao->conteudo_resposta)) !!}
                    </div>

                    <div style="margin-top: 15px; border-top: 1px solid #e2e8f0; padding-top: 10px; font-size: 0.85rem; color: #64748b;">
                        <i class="fa-solid fa-user-pen"></i> Respondido por: <strong>{{ $sugestao->respondido_por ?? 'Sistema' }}</strong>
                    </div>
                </div>
            @else
                @if ($errors->any())
                    <div class="alert-box error" style="background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; margin-bottom: 15px;">
                        <ul style="margin: 0; padding-left: 20px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('sugestoes.update', $sugestao->id) }}" method="POST" id="form-responder" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="resposta">Escreva a resposta abaixo:</label>
                        <textarea 
                            name="resposta" 
                            id="resposta" 
                            rows="8" 
                            class="form-control {{ $errors->has('resposta') ? 'is-invalid' : '' }}" 
                            placeholder="Olá {{ $sugestao->nome ?? 'estudante' }}, agradecemos sua sugestão..."
                            required
                            minlength="10"></textarea>
                        
                        <div class="invalid-feedback" id="resposta-error" style="display:none; color: #dc2626; font-size: 0.85rem; margin-top: 5px;">
                            A resposta precisa ter pelo menos 10 caracteres.
                        </div>
                    </div>

                    <div class="visibility-notice">
                        <div class="notice-icon"><i class="fa-solid fa-eye"></i></div>
                        <div class="notice-text">
                            <strong>Atenção:</strong> Ao enviar, esta resposta ficará <u>pública no sistema</u> 
                            @if($sugestao->email) e uma cópia será enviada para <b>{{ $sugestao->email }}</b>.@endif
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-lg" id="btn-submit">
                            <span class="btn-text"><i class="fa-solid fa-paper-plane"></i> Enviar Resposta</span>
                            <span class="btn-loader" style="display: none;"><i class="fa-solid fa-circle-notch fa-spin"></i> Enviando...</span>
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection


@push('scripts')
<script>
    // Função global para exibir Toaster
    function showToast(message, type = 'success') {
        const container = document.getElementById('toast-container');
        
        // Cria o elemento
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        
        const iconClass = type === 'success' ? 'fa-circle-check' : 'fa-circle-xmark';
        
        toast.innerHTML = `
            <i class="fa-solid ${iconClass}" style="font-size: 1.2rem;"></i>
            <div class="toast-content">${message}</div>
        `;
        
        container.appendChild(toast);
        
        // Anima entrada
        requestAnimationFrame(() => {
            toast.classList.add('show');
        });
        
        // Remove após 4 segundos
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 400); // Espera animação de saída
        }, 4000);
    }

    document.addEventListener('DOMContentLoaded', () => {
        
        // 1. Verifica se existe mensagem de SUCESSO na sessão do Laravel
        @if(session('success'))
            showToast("{{ session('success') }}", 'success');
        @endif

        // 2. Lógica de validação do formulário
        const form = document.getElementById('form-responder');
        const textarea = document.getElementById('resposta');
        const errorMsg = document.getElementById('resposta-error');
        
        if(form) {
            // Remove erro ao digitar
            textarea.addEventListener('input', function() {
                if(this.value.length >= 10) {
                    this.classList.remove('is-invalid');
                    errorMsg.style.display = 'none';
                }
            });

            form.addEventListener('submit', function(e) {
                let isValid = true;
                const valor = textarea.value.trim();

                // Validação Customizada
                if(valor.length < 10) {
                    e.preventDefault(); // Impede envio
                    textarea.classList.add('is-invalid');
                    errorMsg.style.display = 'block';
                    errorMsg.innerText = 'A resposta deve ter pelo menos 10 caracteres.';
                    isValid = false;
                    
                    // Feedback visual de erro
                    showToast('Corrija os erros antes de enviar.', 'error');
                }

                // Se estiver válido, ativa o loading
                if(isValid) {
                    const btn = document.getElementById('btn-submit');
                    const btnText = btn.querySelector('.btn-text');
                    const btnLoader = btn.querySelector('.btn-loader');

                    btn.disabled = true;
                    btnText.style.display = 'none';
                    btnLoader.style.display = 'inline-block';
                    // O form prossegue com o submit
                }
            });
        }
    });
</script>
@endpush