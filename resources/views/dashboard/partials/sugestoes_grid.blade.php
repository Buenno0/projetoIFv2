@forelse($sugestoes as $sugestao)
    <div class="sugestao-card" id="sugestao-card-{{ $sugestao->id }}" style="animation-delay: {{ $loop->index * 50 }}ms">
        <div class="card-top">
            <span class="card-id">#{{ $sugestao->id }}</span>
            <span>{{ optional($sugestao->created_at)->format('d/m/Y H:i') }}</span>
        </div>

        <div class="card-body-text">
            {{ \Illuminate\Support\Str::limit($sugestao->conteudo, 150) }}
        </div>

        <div class="card-footer">
            <div class="author-info">
                <i class="fa-solid fa-user-circle" style="color:#cbd5e1; font-size:1.2rem;"></i>
                <span style="font-weight:600; color:#334155;">{{ $sugestao->nome ?? 'Anônimo' }}</span>
            </div>

            <div style="display:flex; justify-content:space-between; align-items:center; margin-top:10px;">
                @if($sugestao->respondido)
                    <div class="status-badge" style="background:#dcfce7; color:#166534;">
                        <i class="fa-solid fa-check"></i> Respondido por {{ $sugestao->respondido_por ?? 'Desconhecido' }}
                    </div>
                @else
                    <div class="status-badge" style="background:#fee2e2; color:#991b1b;">
                        <i class="fa-regular fa-clock"></i> Pendente
                    </div>
                @endif

                <div class="card-actions">
                    <button class="action-btn btn-reply" onclick="window.location.href='{{ route('sugestoes.responder', $sugestao->id) }}'" title="Responder">
                        <i class="fa-solid fa-reply"></i>
                    </button>

                    @if(!$sugestao->respondido)
                        <button class="action-btn btn-delete" onclick="abrirModal('{{ route('sugestoes.destroy', $sugestao->id) }}', {{ $sugestao->id }})" title="Excluir">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="empty-state" style="grid-column: 1/-1; text-align:center; padding: 40px; color: #94a3b8;">
        <i class="fa-solid fa-folder-open" style="font-size: 3rem; margin-bottom: 15px;"></i>
        <p style="font-size: 1.1rem;">Nenhum registro encontrado.</p>
    </div>
@endforelse