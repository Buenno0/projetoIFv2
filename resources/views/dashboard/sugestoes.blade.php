
    <link rel="stylesheet" href="{{ asset('css/menu-dashboard.css') }}">

@include('dashboard.includes.sidebar')

<main class="main-content p-4">
    @include('dashboard.includes.header')

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h4>
                    <i class="fa-solid fa-lightbulb"></i> Sugestões Recebidas
                </h4>
                <span class="badge">
                    Total: {{ $sugestoes->count() }}
                </span>
            </div>
            <div class="card-body">
                @if($sugestoes->isEmpty())
                    <div class="empty-state">
                        <i class="fa-solid fa-circle-info"></i>
                        <p>Não há sugestões no momento.</p>
                    </div>
                @else
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Mensagem</th>
                                    <th>Autor</th>
                                    <th>Data</th>
                                    <th>Respondido</th>

                                    <th class="text-right">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sugestoes as $index => $sugestao)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ Str::limit($sugestao->conteudo, 50) }}</td>
                                        <td>{{ $sugestao->nome ?? 'Anônimo' }}</td>
                                        <td>{{ $sugestao->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
    @if($sugestao->respondido)
        <x-bi-check-circle-fill class="text-success" width="18" height="18" aria-label="Respondido"/> Respondido
    @else
        <x-bi-x-circle-fill class="text-danger" width="18" height="18" aria-label="Não respondido"/> Não respondido
    @endif
</td>

                                        <td class="text-right">
                                            <a href="{{ route('sugestoes.responder', $sugestao->id) }}" class="btn btn-success">
                                                <i class="fa-solid fa-reply"></i> Responder
                                            </a>

                                            <button class="btn btn-danger"
                                                onclick="abrirModal('{{ route('sugestoes.destroy', $sugestao->id) }}')">
                                                <i class="fa-solid fa-trash"></i> Apagar
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal customizado -->
    <div id="modal-confirmacao" class="modal">
        <div class="modal-content">
            <h3>Confirmar Exclusão</h3>
            <p>Tem certeza que deseja apagar esta sugestão? Esta ação não pode ser desfeita.</p>
            <div class="modal-actions">
                <button class="btn btn-secondary" onclick="fecharModal()">Cancelar</button>
                <form id="form-delete" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Apagar</button>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
    function abrirModal(actionUrl) {
        document.getElementById('form-delete').action = actionUrl;
        document.getElementById('modal-confirmacao').classList.add('show');
    }
    function fecharModal() {
        document.getElementById('modal-confirmacao').classList.remove('show');
    }
</script>

</html>
