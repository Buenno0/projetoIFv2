@extends('layouts.admin')

@section('title', 'Sugestões Recebidas')

@section('content')
    {{-- ESTILOS (CSS) --}}
    <style>
        /* Badges */
        .badge-corner { padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 700; display: inline-flex; align-items: center; gap: 5px; text-transform: uppercase; letter-spacing: 0.5px; }
        .badge-corner.pendente { background: #fee2e2; color: #991b1b; }
        .badge-corner.analise { background: #fef3c7; color: #b45309; }
        .badge-corner.respondida { background: #dcfce7; color: #166534; }
        .footer-user-info { font-size: 0.8rem; color: #64748b; font-weight: 600; display: flex; align-items: center; gap: 6px; }

        /* Barra de Filtros Moderna */
        .modern-filter-bar { display: flex; flex-wrap: wrap; gap: 12px; align-items: center; background: #fff; padding: 16px; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); margin-bottom: 24px; border: 1px solid #f1f5f9; }
        .input-wrapper, .search-container { position: relative; display: flex; align-items: center; }
        .icon-absolute { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; pointer-events: none; font-size: 0.9rem; z-index: 2; }
        
        .modern-select { appearance: none; -webkit-appearance: none; padding: 10px 36px 10px 36px; font-size: 0.9rem; font-weight: 600; color: #475569; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; cursor: pointer; transition: all 0.2s; min-width: 160px; }
        .modern-select:hover { background-color: #f1f5f9; border-color: #cbd5e1; }
        .modern-select:focus { outline: none; border-color: #6366f1; background-color: #fff; }
        
        .search-container { flex-grow: 1; min-width: 250px; }
        .modern-input { width: 100%; padding: 10px 12px 10px 38px; border-radius: 8px; border: 1px solid #e2e8f0; background: #f8fafc; font-weight: 500; color: #334155; transition: all 0.2s; }
        .modern-input:focus { outline: none; background: #fff; border-color: #6366f1; }
        
        .input-wrapper::after { content: '\f078'; font-family: 'Font Awesome 6 Free'; font-weight: 900; position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; pointer-events: none; font-size: 0.8rem; }
        .filter-separator { height: 24px; width: 1px; background: #e2e8f0; margin: 0 4px; }
    </style>

    <div class="dashboard-card" style="background: transparent; box-shadow: none; padding: 0;">
        
        {{-- HEADER --}}
        <div class="dashboard-header" style="background: #fff; padding: 20px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div class="header-title">
                <i class="fa-solid fa-lightbulb" style="color: #16a34a;"></i> 
                Sugestões Recebidas
                <div class="tooltip-wrapper">
                    <i class="fa-solid fa-circle-info help-icon"></i>
                    <div class="tooltip-content">
                        <strong>Legenda:</strong><br>
                        🔴 Pendente (Data de Criação)<br>
                        🟡 Em Análise (Tempo Decorrido)<br>
                        🟢 Respondida (Data da Resposta)
                    </div>
                </div>
            </div> 
            <div class="header-controls">
                <span class="badge-total" id="badge-total" style="background: #f1f5f9; color: #64748b; padding: 5px 10px; border-radius: 6px; font-size: 0.85rem; font-weight: 600;">
                    Carregando...
                </span>
            </div>
        </div>

        {{-- BARRA DE FILTROS --}}
        <div class="modern-filter-bar">
            {{-- Busca --}}
            <div class="search-container">
                <i class="fa-solid fa-search icon-absolute"></i>
                <input type="text" id="busca" placeholder="Buscar por conteúdo, nome ou ID..." class="modern-input">
            </div>

            {{-- Status --}}
            <div class="input-wrapper">
                <i class="fa-solid fa-filter icon-absolute"></i>
                <select id="filter-status" class="modern-select">
                    <option value="">Todos os Status</option>
                    <option value="pendente">🔴 Pendentes</option>
                    <option value="em_analise">🟡 Em Análise</option>
                    <option value="respondida">🟢 Respondidas</option>
                </select>
            </div>

             {{-- Escopo --}}
             <div class="input-wrapper">
                <i class="fa-solid fa-user-tag icon-absolute"></i>
                <select id="filter-scope" class="modern-select">
                    <option value="todas">Todas as Sugestões</option>
                    <option value="minhas">⭐ Minhas Análises</option>
                </select>
            </div>

            <div class="filter-separator"></div>

            {{-- Ordenação --}}
             <div class="input-wrapper">
                <i class="fa-solid fa-arrow-down-short-wide icon-absolute"></i>
                <select id="filter-order" class="modern-select" style="min-width: 140px;">
                    <option value="desc" selected>Mais Recentes</option>
                    <option value="asc">Mais Antigas</option>
                </select>
            </div>

            {{-- Por Página --}}
             <div class="input-wrapper" style="flex-grow: 0;">
                <span style="font-size: 0.85rem; color: #666; margin-right: 8px; font-weight: 600;">Exibir:</span>
                <select id="per-page" class="modern-select" style="min-width: 80px; padding-left: 12px;">
                    <option value="8">8</option>
                    <option value="12" selected>12</option>
                    <option value="24">24</option>
                    <option value="48">48</option>
                </select>
            </div>
        </div>

        {{-- AREA DE CONTEÚDO --}}
        <div class="content-area" style="background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            
            {{-- SKELETON LOADING --}}
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
                    </div>
                @endfor
            </div>

            {{-- GRID DE SUGESTÕES (Corrigido: Fechamento da DIV) --}}
            <div id="sugestoes-grid" class="sugestoes-grid"></div>
        
        </div> {{-- FIM content-area --}}

        {{-- PAGINAÇÃO --}}
        <div class="pagination-bar" style="margin-top: 20px; background: #fff; padding: 15px; border-radius: 12px;">
            <button id="prev-page" class="btn btn-page" disabled><i class="fa-solid fa-chevron-left"></i></button>
            <span id="page-info" style="font-size:0.9rem; font-weight:600; color: var(--text-color);">Carregando...</span>
            <button id="next-page" class="btn btn-page" disabled><i class="fa-solid fa-chevron-right"></i></button>
        </div>

    </div> {{-- FIM dashboard-card --}}

    {{-- MODAL --}}
    <x-modal id="modal-delete" title="Descartar Sugestão?">
        Você tem certeza que deseja descartar essa sugestão?
        <x-slot name="actions">
            <form id="form-delete" method="POST" onsubmit="return false">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger" id="btn-confirmar-delete">Sim, Descartar</button>
            </form>
        </x-slot>
    </x-modal>
@endsection

@push('scripts')
<script>
    let state = {
        page: 1, perPage: 12, q: '',
        status: '', filtro: 'todas', order: 'desc',
        lastPage: 1, total: 0
    };
    let deletandoId = null;

    document.addEventListener('DOMContentLoaded', () => {
        fetchAndRender();
        initEvents();
    });

    // --- FUNÇÃO AUXILIAR PARA SKELETON (Caso não exista globalmente) ---
    function toggleLoading(isLoading, contentId, skeletonId) {
        const content = document.getElementById(contentId);
        const skeleton = document.getElementById(skeletonId);
        if (isLoading) {
            if(content) content.style.display = 'none';
            if(skeleton) skeleton.style.display = 'grid'; // Grid do CSS
        } else {
            if(content) content.style.display = 'grid';
            if(skeleton) skeleton.style.display = 'none';
        }
    }

    function confirmarExclusao(url, id) {
        deletandoId = id;
        document.getElementById('form-delete').action = url;
        abrirModal('modal-delete');
    }

    function initEvents() {
        // Busca
        let timer;
        document.getElementById('busca').addEventListener('input', (e) => {
            clearTimeout(timer);
            timer = setTimeout(() => { state.q = e.target.value.trim(); state.page = 1; fetchAndRender(); }, 500);
        });

        // Filtros
        ['filter-status', 'filter-scope', 'filter-order', 'per-page'].forEach(id => {
            document.getElementById(id).addEventListener('change', (e) => {
                if (id === 'filter-status') state.status = e.target.value;
                if (id === 'filter-scope') state.filtro = e.target.value;
                if (id === 'filter-order') state.order = e.target.value;
                if (id === 'per-page') state.perPage = e.target.value;
                state.page = 1; 
                fetchAndRender();
            });
        });

        // Paginação
        document.getElementById('prev-page').addEventListener('click', () => { if (state.page > 1) { state.page--; fetchAndRender(); } });
        document.getElementById('next-page').addEventListener('click', () => { if (state.page < state.lastPage) { state.page++; fetchAndRender(); } });
        document.getElementById('form-delete').addEventListener('submit', handleDelete);
    }

    async function fetchAndRender() {
        // Chama a função de loading definida acima
        toggleLoading(true, 'sugestoes-grid', 'skeleton-grid');
        
        const params = new URLSearchParams({
            page: state.page, per_page: state.perPage, q: state.q,
            status: state.status, filtro: state.filtro, order: state.order
        });

        try {
            const [resp] = await Promise.all([fetch(`{{ route('dashboard.sugestoes.json') }}?` + params), new Promise(resolve => setTimeout(resolve, 300))]);
            const json = await resp.json();
            if (json.success) { 
                renderGrid(json.data); 
                state.lastPage = json.meta.last_page; 
                state.total = json.meta.total; 
                updatePaginationUI(); 
            }
        } catch (error) { 
            console.error(error);
            showToast('Erro ao carregar dados.', 'error'); 
        } finally { 
            toggleLoading(false, 'sugestoes-grid', 'skeleton-grid'); 
        }
    }

    function renderGrid(items) {
        const grid = document.getElementById('sugestoes-grid');
        grid.innerHTML = '';
        if (!items || items.length === 0) {
            grid.innerHTML = `<div class="empty-state" style="grid-column:1/-1;text-align:center;padding:40px;color:#94a3b8;"><i class="fa-solid fa-folder-open" style="font-size:3rem;margin-bottom:15px;"></i><p>Nenhum registro encontrado.</p></div>`;
            return;
        }

        items.forEach((item, index) => {
            const delay = index * 50;
            let statusValue = item.status;
            if (typeof item.status === 'object' && item.status !== null) statusValue = item.status.value; 

            let topRightHtml = '';
            let bottomLeftHtml = '';
            let isFinalized = false;

            if (statusValue === 'respondida') {
                isFinalized = true;
                topRightHtml = `<span class="badge-corner respondida" title="Respondida em"><i class="fa-solid fa-check-double"></i> ${item.data_resposta_formatada || '-'}</span>`;
                bottomLeftHtml = `<div class="footer-user-info" style="color: #166534;"><i class="fa-solid fa-user-shield"></i> ${item.respondente_nome || 'Staff'}</div>`;
            } else if (statusValue === 'em_analise') {
                const tempoTexto = item.tempo_decorrido || 'Iniciado';
                topRightHtml = `<span class="badge-corner analise" title="Em Análise"><i class="fa-solid fa-stopwatch"></i> ${tempoTexto}</span>`;
                bottomLeftHtml = `<div class="footer-user-info" style="color: #b45309;"><i class="fa-solid fa-user-clock"></i> ${item.analista_nome || 'Staff'}</div>`;
            } else {
                topRightHtml = `<span class="badge-corner pendente" title="Criada em"><i class="fa-regular fa-calendar"></i> ${item.created_at_formatado || '-'}</span>`;
                bottomLeftHtml = `<div class="footer-user-info"></div>`; // Espaço vazio para manter altura
            }

            let actionsHtml = isFinalized 
                ? `<button class="action-btn btn-view" onclick="window.location.href='${item.links.responder}'" title="Exibir Resposta"><i class="fa-solid fa-eye"></i></button>`
                : `<button class="action-btn btn-reply" onclick="window.location.href='${item.links.responder}'" title="Responder"><i class="fa-solid fa-reply"></i></button>
                   <button class="action-btn btn-delete" onclick="confirmarExclusao('${item.links.destroy}', ${item.id})" title="Excluir"><i class="fa-solid fa-trash-can"></i></button>`;

            const card = document.createElement('div');
            card.className = 'sugestao-card';
            card.id = `sugestao-card-${item.id}`;
            card.style.animationDelay = `${delay}ms`;
            
            card.innerHTML = `
                <div class="card-top">
                    <div class="author-info" style="margin:0;">
                        <i class="fa-solid fa-user-circle" style="color:#cbd5e1;font-size:1.2rem;"></i> 
                        <span style="font-weight:600;color:#334155;">${escapeHtml(item.nome || 'Anônimo')}</span>
                    </div>
                    ${topRightHtml}
                </div>
                <div class="card-body-text">${escapeHtml(item.conteudo.length > 140 ? item.conteudo.substring(0, 140) + '...' : item.conteudo)}</div>
                <div class="card-footer">
                    <div style="display:flex;justify-content:space-between;align-items:center;width:100%;margin-top:10px; min-height:24px;">
                        <div>${bottomLeftHtml}</div>
                        <div class="card-actions" style="flex-shrink: 0;">${actionsHtml}</div>
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

    async function handleDelete(e) {
        e.preventDefault();
        const btn = document.getElementById('btn-confirmar-delete');
        const originalText = btn.innerHTML;
        btn.disabled = true; btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Apagando...';
        try {
            const resp = await fetch(e.target.action, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json', 'Content-Type': 'application/json' }});
            const data = await resp.json();
            if (resp.ok && data.success) {
                const card = document.getElementById(`sugestao-card-${deletandoId}`);
                if(card) { card.style.opacity = '0'; setTimeout(() => card.remove(), 300); }
                showToast('Sugestão removida com sucesso!'); fecharModal('modal-delete'); state.total--; updatePaginationUI();
            } else { showToast(data.message || 'Erro ao apagar.', 'error'); }
        } catch (err) { showToast('Erro de conexão.', 'error'); } 
        finally { btn.disabled = false; btn.innerHTML = originalText; }
    }

    function escapeHtml(text) { if (!text) return ''; return text.toString().replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;"); }
</script>
@endpush