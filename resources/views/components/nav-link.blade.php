@props(['id', 'title', 'type' => 'danger'])

<div id="{{ $id }}" class="modal" aria-hidden="true" style="display: none;">
    <div class="modal-content">
        <div class="modal-icon" style="background: {{ $type == 'danger' ? '#fee2e2' : '#dcfce7' }}; color: {{ $type == 'danger' ? '#dc2626' : '#16a34a' }};">
            <i class="fa-regular {{ $type == 'danger' ? 'fa-trash-can' : 'fa-check-circle' }}"></i>
        </div>
        
        <h3 class="modal-title">{{ $title }}</h3>
        
        <div class="modal-desc">
            {{ $slot }}
        </div>

        <div class="modal-actions">
            <button class="btn btn-secondary" onclick="fecharModal('{{ $id }}')">Cancelar</button>
            <div id="{{ $id }}-actions">
                {{ $actions ?? '' }}
            </div>
        </div>
    </div>
</div>