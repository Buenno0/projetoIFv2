<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Sugestões</title>

    <!-- Meta CSRF para AJAX -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Seu CSS existente -->
    <link rel="stylesheet" href="{{ asset('css/menu-dashboard.css') }}">
</head>
<body>

@include('dashboard.includes.sidebar')

<main class="main-content p-4">
    @include('dashboard.includes.header')

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h4><i class="fa-solid fa-lightbulb"></i> Sugestões Recebidas</h4>
                <span class="badge">Total: {{ $sugestoes->count() }}</span>
            </div>

            <div class="card-body">
                @if($sugestoes->isEmpty())
                    <div class="empty-state">
                        <i class="fa-solid fa-circle-info"></i>
                        <p>Não há sugestões no momento.</p>
                    </div>
                @else
                    <div class="sugestoes-grid">
                        @foreach($sugestoes as $index => $sugestao)
                            <div class="sugestao-card" id="sugestao-card-{{ $sugestao->id }}">
                                <div class="sugestao-header">
                                    <span class="sugestao-index">#{{ $sugestao->id }}</span>
                                    <span class="sugestao-data">
                                        {{ $sugestao->created_at->format('d/m/Y H:i') }}
                                    </span>
                                </div>

                                <div class="sugestao-conteudo">
                                    {{ Str::limit($sugestao->conteudo, 150) }}
                                </div>

                                <div class="sugestao-autor">
                                    <strong>Autor:</strong> {{ $sugestao->nome ?? 'Anônimo' }}
                                </div>

                                    <div class="sugestao-status">
                                    @if($sugestao->respondido)
                                        <x-bi-check-circle-fill class="text-success" width="18" height="18"/>
                                        <i>
                                            <b class="answer">Respondido por:</b>
                                            <b>{{ Str::limit(explode(' ', $sugestao->respondido_por->name ?? 'Desconhecido')[0], 15) }}</b>
                                            <b class="answer">em:</b>
                                            <b class="sugestao-data">{{ $sugestao->data_resposta?->format('d/m/Y H:i') }}</b>
                                        </i>
                                    @else
                                        <x-bi-x-circle-fill class="text-danger" width="18" height="18"/>
                                        <i><p>Não respondido</p></i>
                                    @endif
                                </div>


                                <div class="sugestao-actions">
                                    <!-- Botão visualizar/responder -->
                                    <button type="button"
                                        onclick="window.location.href='{{ route('sugestoes.responder', $sugestao->id) }}'"
                                        title="Responder"
                                        aria-label="Responder"
                                        style="background:none;border:none;padding:0;cursor:pointer;line-height:0;">
                                        <img src="{{ asset('assets/reply.svg') }}"
                                             alt="Responder"
                                             width="24"
                                             height="24">
                                    </button>

                                    @if($sugestao->respondido)
                                    <!-- Botão apagar (abre modal) -->
                                    <button type="button"
                                        onclick="abrirModal('{{ route('sugestoes.destroy', $sugestao->id) }}', {{ $sugestao->id }})"
                                        title="Apagar"
                                        aria-label="Apagar"
                                        style="background:none;border:none;padding:0;cursor:pointer;line-height:0;margin-right:8px;">
                                        <img src="{{ asset('assets/trash.svg') }}"
                                             alt="Apagar"
                                             width="24"
                                             height="24">
                                    </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
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

    // Abre o modal e configura action + id
    function abrirModal(actionUrl, id) {
        deletandoId = id;
        const form = document.getElementById('form-delete');
        form.action = actionUrl;

        const modal = document.getElementById('modal-confirmacao');
        modal.classList.add('show'); // sua CSS existente deve lidar com .show
    }

    function fecharModal() {
        const modal = document.getElementById('modal-confirmacao');
        modal.classList.remove('show');
        deletandoId = null;
    }

    // Utilitário: pega o CSRF token do meta
    function getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    // Intercepta submit do form para enviar via AJAX DELETE
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('form-delete');
        const btnConfirmar = document.getElementById('btn-confirmar-delete');

        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const url = form.action;
            if (!url) {
                alert('URL de exclusão não definida.');
                return;
            }

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
                    body: JSON.stringify({}) // adicione dados se precisar
                });

                const data = await resp.json().catch(() => ({}));

                if (resp.ok && data && data.success) {
                    // Remove o card imediatamente
                    if (deletandoId !== null) {
                        const card = document.getElementById(`sugestao-card-${deletandoId}`);
                        if (card) {
                            card.remove();
                        }
                    }
                    fecharModal();
                } else {
                    // Mensagem de erro amigável
                    const msg = (data && data.message) ? data.message : 'Falha ao apagar a sugestão.';
                    alert(msg);
                }

            } catch (err) {
                console.error('Erro ao apagar:', err);
                alert('Erro de rede ao tentar apagar.');
            } finally {
                btnConfirmar.disabled = false;
            }
        });
    });
</script>

</body>
</html>
