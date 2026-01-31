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

            {{-- ================================================================= --}}
            {{-- CARD ESQUERDA: DETALHES DA SUGESTÃO                               --}}
            {{-- ================================================================= --}}
            <div>
                <div class="original-message-box card-full-height">

                    <div class="section-header-row">
                        <h3 class="section-label">Detalhes da Sugestão</h3>

                        <div class="status-top">
                            @php
                                $statusStr = is_object($sugestao->status)
                                    ? $sugestao->status->value
                                    : $sugestao->status;
                            @endphp

                            @if ($statusStr === 'respondida')
                                <span class="badge badge-success"><i class="fa-solid fa-check"></i> Respondida</span>
                            @elseif($statusStr === 'em_analise')
                                <span class="badge badge-warning" style="background:#fef3c7; color:#b45309;">
                                    <i class="fa-solid fa-magnifying-glass"></i> Em Análise
                                </span>
                            @else
                                <span class="badge badge-danger" style="background:#fee2e2; color:#991b1b;">
                                    <i class="fa-regular fa-clock"></i> Pendente
                                </span>
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
                                @if ($sugestao->email)
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
            {{-- CARD DIREITA: LÓGICA DE ESTADOS                                   --}}
            {{-- ================================================================= --}}
            <div>
                @if ($statusStr === 'respondida')

                    {{-- CENÁRIO 1: JÁ RESPONDIDA (Apenas Leitura) --}}
                    <div class="original-message-box card-full-height admin-theme">
                        <div class="section-header-row">
                            <h3 class="section-label" style="color: var(--primary-color);">Resposta Enviada</h3>
                        </div>

                        <div class="user-profile-header">
                            <div class="avatar-circle">
                                <i class="fa-solid fa-user-shield"></i>
                            </div>
                            <div>
                                <div class="user-name">{{ $sugestao->respondido_por ?? 'Equipe de Suporte' }}</div>
                                <div class="meta-info">
                                    <span
                                        style="color: var(--primary-color); font-weight: 600;">{{ $cargoRespondente }}</span>
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

                        {{-- CATEGORIA CLASSIFICADA --}}
                        @if ($sugestao->categoria)
                            <div
                                style="margin-top: 20px; padding: 12px 16px; background: #f1f5f9; border-radius: 8px; display: flex; align-items: center; gap: 10px;">
                                <i class="fa-solid fa-tag" style="color: #6366f1; font-size: 1rem;"></i>
                                <div>
                                    <div
                                        style="font-size: 0.75rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">
                                        Categoria</div>
                                    <div style="font-weight: 600; color: #334155;">
                                        {{ \App\Models\Sugestao::CATEGORIAS[$sugestao->categoria] ?? $sugestao->categoria }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @elseif($statusStr === 'pendente')
                    {{-- CENÁRIO 2: PENDENTE (Botão para iniciar ação) --}}
                    <div class="original-message-box card-full-height"
                        style="border-color: #cbd5e1; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; min-height: 300px;">

                        <div style="background: #f1f5f9; padding: 20px; border-radius: 50%; margin-bottom: 20px;">
                            <i class="fa-solid fa-user-lock" style="font-size: 2.5rem; color: #64748b;"></i>
                        </div>

                        <h3 style="color: #334155; margin-bottom: 10px;">Esta sugestão está pendente</h3>
                        <p style="color: #64748b; margin-bottom: 25px; max-width: 80%;">
                            Para responder, você precisa assumir a responsabilidade por esta análise.
                        </p>

                        {{-- Adicionei a classe 'prevent-double-submit' --}}
                        <form action="{{ route('sugestoes.iniciar_analise', $sugestao->id) }}" method="POST"
                            class="prevent-double-submit">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg"
                                style="padding: 12px 24px; font-size: 1rem;">
                                <i class="fa-solid fa-hand-point-up"></i> Assumir Análise
                            </button>
                        </form>

                    </div>
                @else
                    {{-- CENÁRIO 3: EM ANÁLISE --}}
                    <div class="original-message-box card-full-height" style="border-color: #cbd5e1;">
                        <div class="section-header-row">
                            <h3 class="section-label">Escrever Resposta</h3>
                        </div>

                        @php
                            // Verifica se o usuário logado é quem está analisando
                            $isMyAnalysis = $sugestao->id_user_analysing == auth()->id();
                            $analystName = $sugestao->usuarioQueAnalisou->name ?? 'Usuário Desconhecido';
                        @endphp

                        {{-- ALERTA DE BLOQUEIO OU INFO --}}
                        <div class="analise-info-box"
                            style="
                        background: {{ $isMyAnalysis ? '#eff6ff' : '#fff7ed' }}; 
                        border: 1px solid {{ $isMyAnalysis ? '#dbeafe' : '#ffedd5' }}; 
                        border-radius: 8px; padding: 12px; margin-bottom: 20px; 
                        display: flex; align-items: center; gap: 12px;">

                            {{-- ÍCONE DE USER (Solicitado) --}}
                            <div
                                style="width: 40px; height: 40px; 
                                    background: {{ $isMyAnalysis ? '#bfdbfe' : '#fed7aa' }}; 
                                    color: {{ $isMyAnalysis ? '#1e40af' : '#c2410c' }}; 
                                    border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                                <i class="fa-solid fa-user"></i>
                            </div>

                            <div>
                                <div
                                    style="font-size: 0.95rem; color: {{ $isMyAnalysis ? '#1e40af' : '#9a3412' }}; font-weight: 600;">
                                    @if ($isMyAnalysis)
                                        Você está analisando esta sugestão
                                    @else
                                        <i class="fa-solid fa-lock" style="margin-right:5px;"></i> Em análise por
                                        {{ $analystName }}
                                    @endif
                                </div>
                                <div style="font-size: 0.8rem; color: {{ $isMyAnalysis ? '#60a5fa' : '#ea580c' }};">
                                    Iniciado em {{ optional($sugestao->data_analise)->format('d/m/Y \à\s H:i') }}
                                </div>
                            </div>
                        </div>

                        {{-- LÓGICA DE BLOQUEIO: SÓ MOSTRA O FORM SE FOR O DONO DA ANÁLISE --}}
                        @if ($isMyAnalysis)

                            @if ($errors->any())
                                <div class="alert-box error"
                                    style="background: #fee2e2; color: #991b1b; padding: 10px; border-radius: 8px; margin-bottom: 15px;">
                                    <ul style="margin: 0; padding-left: 20px;">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            {{-- Adicionei a classe 'prevent-double-submit' --}}
                            <form action="{{ route('sugestoes.update', $sugestao->id) }}" method="POST"
                                id="form-responder" class="prevent-double-submit">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <textarea name="resposta" id="resposta" rows="8"
                                        class="form-control {{ $errors->has('resposta') ? 'is-invalid' : '' }}"
                                        placeholder="Digite sua resposta aqui para finalizar o atendimento..." style="min-height: 150px; resize: none;"
                                        required minlength="10"></textarea>

                                    <div class="invalid-feedback" id="resposta-error"
                                        style="display:none; color: #dc2626; margin-top: 5px;">
                                        Mínimo de 10 caracteres.
                                    </div>
                                </div>

                                {{-- CAMPO DE CATEGORIA --}}
                                <div class="form-group" style="margin-top: 20px;">
                                    <label for="categoria"
                                        style="display: block; font-weight: 600; color: #334155; margin-bottom: 8px;">
                                        <i class="fa-solid fa-tag" style="margin-right: 6px; color: #6366f1;"></i>
                                        Classificar Categoria <span style="color: #dc2626;">*</span>
                                    </label>
                                    <select name="categoria" id="categoria"
                                        class="form-control {{ $errors->has('categoria') ? 'is-invalid' : '' }}"
                                        style="padding: 12px 15px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 0.95rem; cursor: pointer; background: #fff;"
                                        required>
                                        <option value="">-- Selecione a categoria --</option>
                                        @foreach (\App\Models\Sugestao::CATEGORIAS as $key => $label)
                                            <option value="{{ $key }}"
                                                {{ old('categoria', $sugestao->categoria) == $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('categoria')
                                        <div class="invalid-feedback"
                                            style="display: block; color: #dc2626; margin-top: 5px;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="visibility-notice" style="margin-top: 15px;">
                                    <div class="notice-icon"><i class="fa-solid fa-eye"></i></div>
                                    <div class="notice-text">
                                        Ao enviar, o status mudará para <strong>Respondida</strong>.
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary" id="btn-submit">
                                        <i class="fa-solid fa-paper-plane"></i> Finalizar e Enviar
                                    </button>
                                </div>
                            </form>
                        @else
                            {{-- BLOQUEADO PARA OUTROS USUÁRIOS --}}
                            <div style="text-align: center; padding: 40px 20px; color: #64748b;">
                                <i class="fa-solid fa-file-shield"
                                    style="font-size: 3rem; margin-bottom: 15px; color: #cbd5e1;"></i>
                                <p>
                                    <strong>Aguarde a finalização.</strong><br>
                                    O usuário <strong>{{ $analystName }}</strong> está trabalhando nesta resposta no
                                    momento.<br>
                                    Se necessário, solicite que ele cancele a análise.
                                </p>
                            </div>
                        @endif
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
            if (!container) return alert(message);

            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            const iconClass = type === 'success' ? 'fa-circle-check' : 'fa-circle-xmark';
            toast.innerHTML =
                `<i class="fa-solid ${iconClass}" style="font-size: 1.2rem;"></i><div class="toast-content">${message}</div>`;
            container.appendChild(toast);
            requestAnimationFrame(() => {
                toast.classList.add('show');
            });
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 400);
            }, 4000);
        }

        document.addEventListener('DOMContentLoaded', () => {
            @if (session('success'))
                showToast("{{ session('success') }}", 'success');
            @endif
            @if (session('error'))
                showToast("{{ session('error') }}", 'error');
            @endif

            // --- PREVENÇÃO DE DUPLO SUBMIT (GLOBAL) ---
            const forms = document.querySelectorAll('.prevent-double-submit');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const btn = form.querySelector('button[type="submit"]');

                    // Validação básica do textarea se for o formulário de resposta
                    if (form.id === 'form-responder') {
                        const textarea = document.getElementById('resposta');
                        if (textarea && textarea.value.trim().length < 10) {
                            e.preventDefault(); // Impede envio se inválido
                            return; // Não desabilita o botão
                        }
                    }

                    // Se passou, desabilita visualmente
                    if (btn && !btn.disabled) {
                        const originalWidth = btn.offsetWidth; // Mantém largura para não pular
                        btn.style.width = `${originalWidth}px`;
                        btn.disabled = true;
                        btn.innerHTML =
                            '<i class="fa-solid fa-spinner fa-spin"></i> Processando...';
                    }
                });
            });

            // Validação em tempo real do Textarea (apenas se existir)
            const textarea = document.getElementById('resposta');
            const errorMsg = document.getElementById('resposta-error');

            if (textarea) {
                textarea.addEventListener('input', function() {
                    if (this.value.length >= 10) {
                        this.classList.remove('is-invalid');
                        errorMsg.style.display = 'none';
                    }
                });

                // Validação extra no submit do form de resposta (apenas visual, a lógica real está acima)
                const responseForm = document.getElementById('form-responder');
                if (responseForm) {
                    responseForm.addEventListener('submit', function(e) {
                        const valor = textarea.value.trim();
                        if (valor.length < 10) {
                            e.preventDefault();
                            textarea.classList.add('is-invalid');
                            errorMsg.style.display = 'block';
                            errorMsg.innerText = 'A resposta deve ter pelo menos 10 caracteres.';
                            showToast('Corrija os erros antes de enviar.', 'error');
                            // Reabilita o botão caso tenha sido desabilitado pelo script global
                            const btn = responseForm.querySelector('button[type="submit"]');
                            if (btn) {
                                btn.disabled = false;
                                btn.innerHTML =
                                    '<i class="fa-solid fa-paper-plane"></i> Finalizar e Enviar';
                            }
                        }
                    });
                }
            }
        });
    </script>
@endpush
