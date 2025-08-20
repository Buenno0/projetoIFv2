<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Auditorias</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light p-4">

<div class="container">
    <h1 class="mb-4">Logs de Auditoria</h1>

    <table class="table table-striped table-bordered align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Evento</th>
                <th>Usuário</th>
                <th>Model Auditado</th>
                <th>Valores Antigos</th>
                <th>Valores Novos</th>
                <th>IP</th>
                <th>Data</th>
            </tr>
        </thead>
        <tbody>
            @foreach($audits as $audit)
                <tr>
                    <td>{{ $audit->id }}</td>
                    <td><span class="badge bg-info">{{ $audit->event }}</span></td>
                    
                    {{-- Usuário (se existir) --}}
                    <td>
                        @if($audit->user)
                            {{ $audit->user->name }} (ID: {{ $audit->user->id }})
                        @else
                            <em>Não autenticado</em>
                        @endif
                    </td>

                    {{-- Model auditado --}}
                    <td>
                        {{ class_basename($audit->auditable_type) }} (ID: {{ $audit->auditable_id }})
                    </td>

                    {{-- old_values / new_values --}}
                    <td>
                        <pre class="small bg-light p-2">{{ json_encode($audit->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </td>
                    <td>
                        <pre class="small bg-light p-2">{{ json_encode($audit->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </td>

                    {{-- IP e Data --}}
                    <td>{{ $audit->ip_address }}</td>
                    <td>{{ $audit->created_at->format('d/m/Y H:i:s') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

</body>
</html>
