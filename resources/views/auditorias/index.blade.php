@extends('layouts.admin')

@section('title', 'Auditoria do Sistema')

@section('content')
<style>
    /* CSS Específico para o Comparador de Auditoria */
    .diff-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 15px;
        margin-top: 10px;
        font-family: monospace;
        font-size: 0.9rem;
    }
    .diff-item {
        display: grid;
        grid-template-columns: 1fr 20px 1fr; /* Antes | Seta | Depois */
        gap: 10px;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px dashed #cbd5e1;
    }
    .diff-item:last-child { border-bottom: none; }
    
    .val-old { color: #dc2626; background: #fee2e2; padding: 2px 6px; border-radius: 4px; word-break: break-all; }
    .val-new { color: #166534; background: #dcfce7; padding: 2px 6px; border-radius: 4px; word-break: break-all; }
    .field-name { font-weight: bold; color: #475569; display: block; margin-bottom: 4px; }
    
    .meta-info {
        font-size: 0.85rem;
        color: #64748b;
        margin-bottom: 15px;
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }
    .meta-tag { display: flex; align-items: center; gap: 6px; }
</style>

<div class="dashboard-card">
    <div class="dashboard-header">
        <div class="header-title">
            <i class="fa-solid fa-shield-halved"></i> Logs de Auditoria
        </div>
        <div class="header-controls">
            <div style="font-size: 0.85rem; color: #64748b; margin-right: 15px;">
                <i class="fa-solid fa-circle" style="color: #16a34a;"></i> Criou
                <i class="fa-solid fa-circle" style="color: #2563eb;"></i> Editou
                <i class="fa-solid fa-circle" style="color: #dc2626;"></i> Apagou
            </div>

            <a href="{{ route('audits.download') }}" class="btn" style="background: var(--primary-color); color: white;">
                <i class="fa-solid fa-file-export"></i> Exportar JSON
            </a>
            <span class="badge-total">{{ $audits->total() }} eventos</span>
        </div>
    </div>

    <div class="content-area">
        <div class="table-responsive">
            <table class="table" style="width: 100%; border-collapse: separate; border-spacing: 0;">
                <thead>
                    <tr style="text-align: left; background: #f8fafc; color: #475569;">
                        <th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Evento</th>
                        <th style="border-bottom: 2px solid #e2e8f0;">Quem fez?</th>
                        <th style="border-bottom: 2px solid #e2e8f0;">Onde? (Model)</th>
                        <th style="border-bottom: 2px solid #e2e8f0;">IP / Data</th>
                        <th style="border-bottom: 2px solid #e2e8f0; text-align: right;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($audits as $audit)
                    <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        
                        <td style="padding: 12px;">
                            @php
                                $colors = [
                                    'created' => ['bg' => '#dcfce7', 'text' => '#166534', 'icon' => 'fa-plus'],
                                    'updated' => ['bg' => '#dbeafe', 'text' => '#1e40af', 'icon' => 'fa-pen'],
                                    'deleted' => ['bg' => '#fee2e2', 'text' => '#991b1b', 'icon' => 'fa-trash'],
                                    'restored'=> ['bg' => '#f3e8ff', 'text' => '#6b21a8', 'icon' => 'fa-rotate-left'],
                                ];
                                $style = $colors[$audit->event] ?? ['bg' => '#f1f5f9', 'text' => '#475569', 'icon' => 'fa-question'];
                            @endphp
                            <span class="status-badge" style="background: {{ $style['bg'] }}; color: {{ $style['text'] }}; padding: 6px 12px; border-radius: 20px; font-weight: 600; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 6px;">
                                <i class="fa-solid {{ $style['icon'] }}"></i> {{ strtoupper($audit->event) }}
                            </span>
                        </td>

                        <td>
                            <div style="display: flex; flex-direction: column;">
                                <span style="font-weight: 600; color: #334155;">
                                    {{ $audit->user->name ?? 'Sistema / Cron' }}
                                </span>
                                <span style="font-size: 0.75rem; color: #94a3b8;">
                                    ID: {{ $audit->user_id ?? 'N/A' }}
                                </span>
                            </div>
                        </td>

                        <td>
                            <span style="background: #f1f5f9; padding: 4px 8px; border-radius: 6px; color: #475569; font-family: monospace; font-size: 0.85rem;">
                                {{ class_basename($audit->auditable_type) }} #{{ $audit->auditable_id }}
                            </span>
                        </td>

                        <td>
                            <div style="display: flex; flex-direction: column; font-size: 0.85rem;">
                                <span style="color: #64748b;"><i class="fa-solid fa-globe"></i> {{ $audit->ip_address }}</span>
                                <span style="color: #334155; font-weight: 500;">{{ $audit->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </td>

                        <td style="text-align: right; padding-right: 12px;">
                            <button class="btn btn-sm btn-outline-secondary" onclick="verDetalhes('{{ $audit->id }}')" title="Ver alterações">
                                <i class="fa-solid fa-eye"></i> Detalhes
                            </button>
                        </td>
                    </tr>

                    <div id="dados-audit-{{ $audit->id }}" style="display: none;">
                        
                        <div class="meta-info">
    @php
        // Tenta decodificar. Se for log antigo, vira NULL. Se for novo, vira Array.
        $meta = json_decode($audit->user_agent, true);
        $isRich = json_last_error() === JSON_ERROR_NONE && is_array($meta);
    @endphp

    <div class="meta-tag" title="Rota e Método">
        @if($isRich)
            <span class="badge" style="background: #475569; color: #fff; padding: 2px 6px; border-radius: 4px; font-size: 0.75rem;">
                {{ $meta['method'] ?? '??' }}
            </span>
            <span style="font-family: monospace; color: #334155;">{{ $meta['route'] ?? 'rota.desconhecida' }}</span>
        @else
            <i class="fa-solid fa-link"></i> URL: {{ Str::limit($audit->url, 30) }}
        @endif
    </div>

    <div class="meta-tag" title="Dispositivo">
        @if($isRich)
            @if($meta['is_mobile'])
                <i class="fa-solid fa-mobile-screen-button" style="color: #d97706;"></i>
            @elseif($meta['is_robot'])
                <i class="fa-solid fa-robot" style="color: #6366f1;"></i>
            @else
                <i class="fa-solid fa-desktop" style="color: #64748b;"></i>
            @endif
            
            <span style="color: #334155;">
                {{ $meta['platform'] }} - {{ $meta['browser'] }}
            </span>
        @else
            <i class="fa-regular fa-compass"></i> {{ Str::limit($audit->user_agent, 25) }}
        @endif
    </div>

    @if($isRich && isset($meta['session_id']))
    <div class="meta-tag" title="ID da Sessão (Rastreabilidade)">
        <i class="fa-solid fa-fingerprint" style="color: #94a3b8;"></i>
        <span style="font-family: monospace; font-size: 0.75rem; color: #94a3b8;">
            {{ Str::limit($meta['session_id'], 8) }}
        </span>
    </div>
    @endif
</div>

                        <div class="diff-box">
                            @if($audit->event == 'created')
                                <div style="text-align: center; color: #166534; padding: 10px;">
                                    <i class="fa-solid fa-plus-circle"></i> Novo registro criado. Valores iniciais:
                                </div>
                                @foreach($audit->new_values as $key => $val)
                                    <div class="diff-item" style="grid-template-columns: 1fr;">
                                        <div>
                                            <span class="field-name">{{ $key }}</span>
                                            <span class="val-new">{{ is_array($val) ? json_encode($val) : $val }}</span>
                                        </div>
                                    </div>
                                @endforeach

                            @elseif($audit->event == 'deleted')
                                <div style="text-align: center; color: #991b1b; padding: 10px;">
                                    <i class="fa-solid fa-trash"></i> Registro apagado. Dados anteriores:
                                </div>
                                @foreach($audit->old_values as $key => $val)
                                    <div class="diff-item" style="grid-template-columns: 1fr;">
                                        <div>
                                            <span class="field-name">{{ $key }}</span>
                                            <span class="val-old">{{ is_array($val) ? json_encode($val) : $val }}</span>
                                        </div>
                                    </div>
                                @endforeach

                            @else
                                @foreach($audit->new_values as $key => $newVal)
                                    @php 
                                        $oldVal = $audit->old_values[$key] ?? 'null';
                                    @endphp
                                    <div class="diff-item">
                                        <div style="text-align: right;">
                                            <span class="field-name">{{ $key }} (Antes)</span>
                                            <span class="val-old">{{ is_array($oldVal) ? json_encode($oldVal) : $oldVal }}</span>
                                        </div>
                                        
                                        <div style="text-align: center; color: #94a3b8;">
                                            <i class="fa-solid fa-arrow-right"></i>
                                        </div>

                                        <div>
                                            <span class="field-name">{{ $key }} (Depois)</span>
                                            <span class="val-new">{{ is_array($newVal) ? json_encode($newVal) : $newVal }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="pagination-bar">
        {{ $audits->links() }}
    </div>
</div>

<x-modal id="modal-detalhes" title="Detalhes da Auditoria" type="info">
    <div id="modal-body-content">
        </div>
    
    <x-slot name="actions">
        <button class="btn btn-secondary" onclick="fecharModal('modal-detalhes')">Fechar</button>
    </x-slot>
</x-modal>

@endsection

@push('scripts')
<script>
    function verDetalhes(id) {
        // 1. Pega o conteúdo oculto daquela linha
        const content = document.getElementById(`dados-audit-${id}`).innerHTML;
        
        // 2. Joga dentro do corpo do modal
        document.getElementById('modal-body-content').innerHTML = content;
        
        // 3. Abre o modal
        abrirModal('modal-detalhes');
    }
</script>
@endpush