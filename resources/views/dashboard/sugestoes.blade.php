@extends('layouts.admin')

@section('title', 'Sugestões Recebidas')

@section('content')
    <div class="dashboard-card">
        
        <div class="dashboard-header">
    <div class="header-title">
        <i class="fa-solid fa-lightbulb"></i> 
        Sugestões Recebidas

        <div class="tooltip-wrapper">
            <i class="fa-solid fa-circle-info help-icon"></i>
            <div class="tooltip-content">
                <strong>Nota:</strong> As informações aqui são resumidas. 
                Sugestões já respondidas não podem receber novas respostas.
            </div>
        </div>
        
    </div> <div class="header-controls">
        </div>
    

            <div class="header-controls">
                <div class="toggle-wrapper" title="Filtrar apenas itens pendentes">
                    <span id="label-toggle" style="font-weight: 600; font-size: 0.9rem;">
                        {{ $apenasNaoRespondidas ? 'Pendentes' : 'Todas' }}
                    </span>
                    <label class="switch">
                        <input type="checkbox" id="toggle-respondidas" {{ $apenasNaoRespondidas ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </div>

                <span class="badge-total" id="badge-total">
                    {{ $sugestoes->total() }} registros
                </span>
            </div>
        </div>

        <div class="toolbar">
            <div class="search-wrapper" style="position: relative; flex-grow: 1; max-width: 400px;">
                <i class="fa-solid fa-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
                <input type="text" id="busca" placeholder="Buscar por conteúdo, ID ou nome..." class="form-control" style="padding-left: 38px; width: 100%;">
            </div>
            
            <div style="display: flex; align-items: center; gap: 8px;">
                <span style="font-size: 0.85rem; color: #666;">Exibir:</span>
                <select id="per-page" class="form-select" style="width: auto; min-width: 80px;">
                    <option value="8">8</option>
                    <option value="12" selected>12</option>
                    <option value="24">24</option>
                </select>
            </div>
        </div>

        <div class="content-area">
            
            <div id="skeleton-grid" class="sugestoes-grid" style="display:none;">
                @for($i = 0; $i < 4; $i++)
                    <div class="skeleton-card">
                        <div style="display:flex; justify-content:space-between; margin-bottom:15px;">
                            <div class="skeleton skeleton-text" style="width: 40px;"></div>
                            <div class="skeleton skeleton-text" style="width: 80px;"></div>
                        </div>
                        <div class="skeleton skeleton-text"></div>
                        <div class="skeleton skeleton-text"></div>
                        <div class="skeleton skeleton-title"></div>
                        <div style="margin-top: 20px; display:flex; justify-content:space-between; align-items:center;">
                            <div style="display:flex; gap:10px; align-items:center;">
                                <div class="skeleton skeleton-avatar"></div>
                                <div class="skeleton skeleton-text" style="width: 100px; margin:0;"></div>
                            </div>
                            <div class="skeleton skeleton-avatar"></div>
                        </div>
                    </div>
                @endfor
            </div>

            <div class="sugestoes-grid" id="sugestoes-grid">
                @forelse($sugestoes as $sugestao)
                    <div class="sugestao-card" id="sugestao-card-{{ $sugestao->id }}">
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
                                        <i class="fa-solid fa-check"></i> Respondido
                                    </div>
                                @else
                                    <div class="status-badge" style="background:#fee2e2; color:#991b1b;">
                                        <i class="fa-regular fa-clock"></i> Pendente
                                    </div>
                                @endif

                                <div class="card-actions">
                                    @if ($sugestao->respondido)
                                        <button class="action-btn btn-view" onclick="window.location.href='{{ route('sugestoes.responder', $sugestao->id) }}'" title="Exibir Resposta">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                    @else
                                    <button class="action-btn btn-reply" onclick="window.location.href='{{ route('sugestoes.responder', $sugestao->id) }}'" title="Responder">
                                        <i class="fa-solid fa-reply"></i>
                                    </button>
                                        <button class="action-btn btn-delete" onclick="confirmarExclusao('{{ route('sugestoes.destroy', $sugestao->id) }}', {{ $sugestao->id }})" title="Excluir">
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
            </div>
        </div>

        <div class="pagination-bar">
            <button id="prev-page" class="btn btn-page" disabled>
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <span id="page-info" style="font-size:0.9rem; font-weight:600; color: var(--text-color);">
                Página 1
            </span>
            <button id="next-page" class="btn btn-page" disabled>
                 <i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>
    </div>

    <x-modal id="modal-delete" title="Apagar Sugestão?">
        Você tem certeza que deseja remover este registro permanentemente do sistema?
        
        <x-slot name="actions">
            <form id="form-delete" method="POST" onsubmit="return false">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger" id="btn-confirmar-delete">Sim, Apagar</button>
            </form>
        </x-slot>
    </x-modal>
@endsection

@push('scripts')
<script>
    // Variáveis de Estado
    let state = {
        apenasNaoRespondidas: {{ $apenasNaoRespondidas ? 'true' : 'false' }},
        page: 1,
        perPage: 12,
        q: '',
        lastPage: {{ method_exists($sugestoes, 'lastPage') ? $sugestoes->lastPage() : 1 }},
        total: {{ method_exists($sugestoes, 'total') ? $sugestoes->total() : $sugestoes->count() }}
    };

    let deletandoId = null;

    // Inicialização
    document.addEventListener('DOMContentLoaded', () => {
        updatePaginationUI();
        initEvents();
    });

    function confirmarExclusao(url, id) {
        deletandoId = id;
        document.getElementById('form-delete').action = url;
        abrirModal('modal-delete');
    }

    function initEvents() {
        // Filtro Toggle
        document.getElementById('toggle-respondidas').addEventListener('change', (e) => {
            state.apenasNaoRespondidas = e.target.checked;
            document.getElementById('label-toggle').innerText = state.apenasNaoRespondidas ? 'Pendentes' : 'Todas';
            resetAndFetch();
        });

        // Itens por página
        document.getElementById('per-page').addEventListener('change', (e) => {
            state.perPage = e.target.value;
            resetAndFetch();
        });

        // Busca
        let timer;
        document.getElementById('busca').addEventListener('input', (e) => {
            clearTimeout(timer);
            timer = setTimeout(() => {
                state.q = e.target.value.trim();
                resetAndFetch();
            }, 500);
        });

        // Paginação
        document.getElementById('prev-page').addEventListener('click', () => {
            if (state.page > 1) { state.page--; fetchAndRender(); }
        });

        document.getElementById('next-page').addEventListener('click', () => {
            if (state.page < state.lastPage) { state.page++; fetchAndRender(); }
        });

        // Deletar
        document.getElementById('form-delete').addEventListener('submit', handleDelete);
    }

    function resetAndFetch() {
        state.page = 1;
        fetchAndRender();
    }

    // Função de Deletar
    async function handleDelete(e) {
        e.preventDefault();
        const btn = document.getElementById('btn-confirmar-delete');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Apagando...';

        try {
            const resp = await fetch(e.target.action, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json', 'Content-Type': 'application/json'
                }
            });
            const data = await resp.json();

            if (resp.ok && data.success) {
                // Remove visualmente
                const card = document.getElementById(`sugestao-card-${deletandoId}`);
                if(card) {
                    card.style.transform = 'scale(0.9) translateY(20px)';
                    card.style.opacity = '0';
                    setTimeout(() => card.remove(), 300);
                }
                showToast('Sugestão removida com sucesso!');
                fecharModal('modal-delete');
                state.total--;
                updatePaginationUI();
            } else {
                showToast(data.message || 'Erro ao apagar.', 'error');
            }
        } catch (err) {
            console.error(err);
            showToast('Erro de conexão.', 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    }

    // Busca e Renderiza (AJAX)
    async function fetchAndRender() {
        toggleLoading(true, 'sugestoes-grid', 'skeleton-grid'); // Usa função global

        const params = new URLSearchParams({
            page: state.page, per_page: state.perPage, q: state.q,
            apenas_nao_respondidas: state.apenasNaoRespondidas ? '1' : '0'
        });

        try {
            const [resp] = await Promise.all([
                fetch(`{{ route('dashboard.sugestoes.json') }}?` + params),
                new Promise(resolve => setTimeout(resolve, 300))
            ]);
            const json = await resp.json();

            if (json.success) {
                renderGrid(json.data);
                state.lastPage = json.meta.last_page;
                state.total = json.meta.total;
                updatePaginationUI();
            }
        } catch (error) {
            showToast('Erro ao carregar dados.', 'error');
        } finally {
            toggleLoading(false, 'sugestoes-grid', 'skeleton-grid');
        }
    }

    function renderGrid(items) {
        const grid = document.getElementById('sugestoes-grid');
        grid.innerHTML = '';

        if (items.length === 0) {
            grid.innerHTML = `<div class="empty-state" style="grid-column:1/-1;text-align:center;padding:40px;color:#94a3b8;"><i class="fa-solid fa-folder-open" style="font-size:3rem;margin-bottom:15px;"></i><p>Nenhum registro encontrado.</p></div>`;
            return;
        }

        items.forEach((item, index) => {
            const delay = index * 50;
            const respondidoHtml = item.respondido 
                ? `<div class="status-badge" style="background:#dcfce7; color:#166534;"><i class="fa-solid fa-check"></i> Respondido</div>`
                : `<div class="status-badge" style="background:#fee2e2; color:#991b1b;"><i class="fa-regular fa-clock"></i> Pendente</div>`;
            
            const deleteBtn = !item.respondido 
                ? `<button class="action-btn btn-delete" onclick="confirmarExclusao('${item.links.destroy}', ${item.id})" title="Excluir"><i class="fa-solid fa-trash-can"></i></button>` : '';

            const card = document.createElement('div');
            card.className = 'sugestao-card';
            card.id = `sugestao-card-${item.id}`;
            card.style.animationDelay = `${delay}ms`;
            
            card.innerHTML = `
                <div class="card-top"><span class="card-id">#${item.id}</span><span>${item.created_at_formatado || item.created_at}</span></div>
                <div class="card-body-text">${escapeHtml(item.conteudo.length > 140 ? item.conteudo.substring(0, 140) + '...' : item.conteudo)}</div>
                <div class="card-footer">
                    <div class="author-info"><i class="fa-solid fa-user-circle" style="color:#cbd5e1;font-size:1.2rem;"></i> <span style="font-weight:600;color:#334155;">${escapeHtml(item.nome || 'Anônimo')}</span></div>
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-top:10px;">
                        ${respondidoHtml}
                        <div class="card-actions">
                            <button class="action-btn btn-reply" onclick="window.location.href='${item.links.responder}'" title="Responder"><i class="fa-solid fa-reply"></i></button>
                            ${deleteBtn}
                        </div>
                    </div>
                </div>`;
            grid.appendChild(card);
        });
    }

    function updatePaginationUI() {
        document.getElementById('badge-total').innerText = `${state.total} registros`;
        document.getElementById('page-info').innerText = `Página ${state.page} de ${state.lastPage}`;
        document.getElementById('prev-page').disabled = state.page <= 1;
        document.getElementById('next-page').disabled = state.page >= state.lastPage;
    }

    function escapeHtml(text) {
        if (!text) return '';
        return text.toString().replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
    }
</script>
@endpush