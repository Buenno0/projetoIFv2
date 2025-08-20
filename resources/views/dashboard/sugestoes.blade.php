<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Sugestões</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('css/menu-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fontawesome.css') }}">

</head>
<body>

@include('dashboard.includes.sidebar')

<main class="main-content p-4">
    @include('dashboard.includes.header')

    <div class="container">
        <div class="card">
            @php
                $soNaoRespondidas = isset($apenasNaoRespondidas)
                    ? (bool) $apenasNaoRespondidas
                    : request()->boolean('apenas_nao_respondidas');

                $rotaBase = route('dashboard.sugestoes');
                $contagemAtual = $sugestoes->count();
            @endphp

            <div class="card-header" style="display:flex; align-items:center; gap:12px;">
                <h4 style="margin:0; display:flex; align-items:center; gap:8px;">
                    <i class="fa-solid fa-lightbulb"></i>
                    Sugestões Recebidas
                </h4>

                <div style="margin-left:auto; display:flex; gap:12px; align-items:center;">
                    <span id="label-toggle">{{ $soNaoRespondidas ? 'Apenas não respondidas' : 'Todas' }}</span>
                    <label class="switch">
                        <input type="checkbox" id="toggle-respondidas" {{ $soNaoRespondidas ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </div>

                <span class="badge" id="badge-total">
                    Total: {{ $contagemAtual }}@if(isset($totalGeral)) / {{ $totalGeral }} @endif
                </span>
            </div>

            <div class="card-body">
                <div id="filtros-extras" style="display:flex; gap:8px; align-items:center; margin-bottom:8px;">
                    <input type="text" id="busca" placeholder="Buscar por texto..." class="form-control" style="max-width:280px;">
                    <select id="per-page" class="form-select" style="width:auto;">
                        <option value="8">8</option>
                        <option value="12" selected>12</option>
                        <option value="24">24</option>
                    </select>
                </div>

                <div id="loader" class="spinner" style="display:none;"></div>


                {{-- Render inicial (substituído via AJAX após o load) --}}
                <div class="sugestoes-grid" id="sugestoes-grid">
                    @forelse($sugestoes as $sugestao)
                        <div class="sugestao-card" id="sugestao-card-{{ $sugestao->id }}">
                            <div class="sugestao-header">
                                <span class="sugestao-index">#{{ $sugestao->id }}</span>
                                <span class="sugestao-data">{{ optional($sugestao->created_at)->format('d/m/Y H:i') }}</span>
                            </div>

                            <div class="sugestao-conteudo">
                                {{ \Illuminate\Support\Str::limit($sugestao->conteudo, 150) }}
                            </div>

                            <div class="sugestao-autor">
                                <strong>Autor:</strong> {{ $sugestao->nome ?? 'Anônimo' }}
                            </div>

                            <div class="sugestao-status">
                                @if($sugestao->respondido)
                                    <x-bi-check-circle-fill class="text-success" width="18" height="18"/>
                                    <i>
                                        <b class="answer">Respondido por:</b>
                                        <b>{{ \Illuminate\Support\Str::limit(explode(' ', $sugestao->respondido_por ?? 'Desconhecido')[0], 15) }}</b>
                                        <b class="answer">em:</b>
                                        <b class="sugestao-data">{{ optional($sugestao->data_resposta)->format('d/m/Y H:i') }}</b>
                                    </i>
                                @else
                                    <x-bi-x-circle-fill class="text-danger" width="18" height="18"/>
                                    <i><p>Não respondido</p></i>
                                @endif
                            </div>

                            <div class="sugestao-actions" style="display:flex; gap:8px; align-items:center;">
                                <button type="button"
                                        onclick="window.location.href='{{ route('sugestoes.responder', $sugestao->id) }}'"
                                        title="Responder"
                                        aria-label="Responder"
                                        style="background:none;border:none;padding:0;cursor:pointer;line-height:0;">
                                    <img src="{{ asset('assets/reply.svg') }}" alt="Responder" width="24" height="24">
                                </button>

                                @if($sugestao->respondido !== true)
                                    <button type="button"
                                            onclick="abrirModal('{{ route('sugestoes.destroy', $sugestao->id) }}', {{ $sugestao->id }})"
                                            title="Apagar"
                                            aria-label="Apagar"
                                            style="background:none;border:none;padding:0;cursor:pointer;line-height:0;margin-right:8px;">
                                        <img src="{{ asset('assets/trash.svg') }}" alt="Apagar" width="24" height="24">
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="fa-solid fa-circle-info"></i>
                            <p>Não há sugestões no momento.</p>
                        </div>
                    @endforelse
                </div>

                <div id="paginacao" style="margin-top:12px; display:flex; gap:8px; align-items:center;">
                    <button id="prev-page" class="btn btn-light btn-sm" disabled>Anterior</button>
                    <span id="page-info"></span>
                    <button id="next-page" class="btn btn-light btn-sm" disabled>Próxima</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="modal-confirmacao" class="modal" role="dialog" aria-modal="true" aria-labelledby="titulo-modal">
        <div class="modal-content">
            <h3 id="titulo-modal">Confirmar Exclusão</h3>
            <p>Tem certeza que deseja apagar esta sugestão? Esta ação não pode ser desfeita.</p>

            <div class="modal-actions">
                <button class="btn btn-secondary" type="button" onclick="fecharModal()">Cancelar</button>

                <form id="form-delete" method="POST" onsubmit="return false">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" id="btn-confirmar-delete">Apagar</button>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
    // Estado do item que está sendo deletado
    let deletandoId = null;

    function abrirModal(actionUrl, id) {
        deletandoId = id;
        const form = document.getElementById('form-delete');
        form.action = actionUrl;
        document.getElementById('modal-confirmacao').classList.add('show');
    }

    function fecharModal() {
        document.getElementById('modal-confirmacao').classList.remove('show');
        deletandoId = null;
    }

    function getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    let state = {
        apenasNaoRespondidas: {{ $soNaoRespondidas ? 'true' : 'false' }},
        page: 1,
        perPage: 12,
        q: '',
        total: 0,
        lastPage: 1
    };

    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('form-delete');
        const btnConfirmar = document.getElementById('btn-confirmar-delete');

        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            const url = form.action;
            if (!url) return alert('URL de exclusão não definida.');
            const token = getCsrfToken();
            btnConfirmar.disabled = true;

            try {
                const resp = await fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({})
                });
                const data = await resp.json().catch(() => ({}));
                if (resp.ok && data && data.success) {
                    if (deletandoId !== null) {
                        const card = document.getElementById(`sugestao-card-${deletandoId}`);
                        if (card) card.remove();
                    }
                    fecharModal();
                    fetchAndRender();
                } else {
                    alert(data?.message || 'Falha ao apagar a sugestão.');
                }
            } catch (err) {
                console.error('Erro ao apagar:', err);
                alert('Erro de rede ao tentar apagar.');
            } finally {
                btnConfirmar.disabled = false;
            }
        });

        document.getElementById('per-page').value = String(state.perPage);
        initEvents();
        fetchAndRender();
    });

    // Toggle do filtro respondidas/não respondidas
    const toggleSwitch = document.getElementById('toggle-respondidas');
let toggleBlocked = false;

toggleSwitch.addEventListener('change', (e) => {
    if (toggleBlocked) return; // impede novos cliques
    toggleBlocked = true;

    state.apenasNaoRespondidas = e.target.checked;
    state.page = 1;
    fetchAndRender();
    document.getElementById('label-toggle').textContent = state.apenasNaoRespondidas
        ? "Apenas não respondidas"
        : "Todas";

    // libera o switch após 1 segundo
    setTimeout(() => {
        toggleBlocked = false;
    }, 1000);
});


    function initEvents() {
        document.getElementById('prev-page').addEventListener('click', () => {
            if (state.page > 1) { state.page--; fetchAndRender(); }
        });
        document.getElementById('next-page').addEventListener('click', () => {
            if (state.page < state.lastPage) { state.page++; fetchAndRender(); }
        });
        const buscaInput = document.getElementById('busca');
        let buscaTimer = null;
        buscaInput.addEventListener('input', () => {
            clearTimeout(buscaTimer);
            buscaTimer = setTimeout(() => {
                state.q = buscaInput.value.trim();
                state.page = 1;
                fetchAndRender();
            }, 300);
        });
        document.getElementById('per-page').addEventListener('change', (e) => {
            state.perPage = parseInt(e.target.value, 10) || 12;
            state.page = 1;
            fetchAndRender();
        });
    }

    async function fetchAndRender() {
        const loader = document.getElementById('loader');
        loader.style.display = 'block';
        const params = new URLSearchParams({
            page: state.page,
            per_page: state.perPage,
        });
        if (state.apenasNaoRespondidas) params.set('apenas_nao_respondidas', '1');
        if (state.q) params.set('q', state.q);

        try {
            const resp = await fetch(`{{ route('dashboard.sugestoes.json') }}?` + params.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest','Accept': 'application/json' }
            });
            const json = await resp.json();
            if (!resp.ok || !json.success) throw new Error(json.message || 'Falha ao carregar sugestões');
            renderGrid(json.data);
            renderMeta(json.meta);
        } catch (err) {
            console.error(err);
            alert('Erro ao carregar sugestões.');
        } finally {
            loader.style.display = 'none';
        }
    }

    function renderGrid(items) {
        const grid = document.getElementById('sugestoes-grid');
        grid.innerHTML = '';
        if (!items || items.length === 0) {
            grid.innerHTML = `
              <div class="empty-state">
                <i class="fa-solid fa-circle-info"></i>
                <p>Não há sugestões no momento.</p>
              </div>`;
            return;
        }
        for (const s of items) {
            const statusHtml = s.respondido
              ? `
              <div class="sugestao-status">
                <svg class="text-success" width="18" height="18" viewBox="0 0 16 16" fill="currentColor">
                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0"/>
                  <path fill="#fff" d="M11.03 5.97a.75.75 0 0 1 0 1.06L7.78 10.28a.75.75 0 0 1-1.06 0L4.97 8.53a.75.75 0 1 1 1.06-1.06l1.22 1.22 2.78-2.78a.75.75 0 0 1 1.06 0z"/>
                </svg>
                <i>
                  <b class="answer">Respondido por:</b>
                  <b>${(s.respondido_por || 'Desconhecido').split(' ')[0].slice(0,15)}</b>
                  <b class="answer">em:</b>
                  <b class="sugestao-data">${s.data_resposta || ''}</b>
                </i>
              </div>`
              : `
              <div class="sugestao-status">
                <svg class="text-danger" width="18" height="18" viewBox="0 0 16 16" fill="currentColor">
                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0"/>
                </svg>
                <i><p>Não respondido</p></i>
              </div>`;
            const card = document.createElement('div');
            card.className = 'sugestao-card';
            card.id = `sugestao-card-${s.id}`;
            card.innerHTML = `
              <div class="sugestao-header">
                <span class="sugestao-index">#${s.id}</span>
                <span class="sugestao-data">${s.created_at || ''}</span>
              </div>
              <div class="sugestao-conteudo">${limitText(s.conteudo, 150)}</div>
              <div class="sugestao-autor">
                <strong>Autor:</strong> ${escapeHtml(s.nome || 'Anônimo')}
              </div>
              ${statusHtml}
              <div class="sugestao-actions" style="display:flex; gap:8px; align-items:center;">
                <button type="button"
                  onclick="window.location.href='${s.links.responder}'"
                  title="Responder" aria-label="Responder"
                  style="background:none;border:none;padding:0;cursor:pointer;line-height:0;">
                  <img src="{{ asset('assets/reply.svg') }}" alt="Responder" width="24" height="24">
                </button>
                ${!s.respondido ? `
                <button type="button"
                  onclick="abrirModal('${s.links.destroy}', ${s.id})"
                  title="Apagar" aria-label="Apagar"
                  style="background:none;border:none;padding:0;cursor:pointer;line-height:0;margin-right:8px;">
                  <img src="{{ asset('assets/trash.svg') }}" alt="Apagar" width="24" height="24">
                </button>` : ''}
              </div>`;
            grid.appendChild(card);
        }
    }

    function renderMeta(meta) {
        state.page = meta.current_page;
        state.lastPage = meta.last_page;
        state.total = meta.total;
        document.getElementById('badge-total').textContent = `Total: ${meta.total} / ${meta.total_geral}`;
        document.getElementById('prev-page').disabled = state.page <= 1;
        document.getElementById('next-page').disabled = state.page >= state.lastPage;
        document.getElementById('page-info').textContent = `Página ${state.page} de ${state.lastPage}`;
    }

    function limitText(text, max) {
        const t = (text || '').toString();
        return t.length <= max ? escapeHtml(t) : escapeHtml(t.slice(0, max)) + '…';
    }

    function escapeHtml(str) {
        return String(str)
            .replaceAll('&','&amp;')
            .replaceAll('<','&lt;')
            .replaceAll('>','&gt;')
            .replaceAll('"','&quot;')
            .replaceAll("'","&#039;");
    }
</script>

</body>
</html>
