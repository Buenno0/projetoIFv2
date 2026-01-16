/* ========================
   Utilitários Globais
   ======================== */

// 1. Toast Notifications
function showToast(message, type = 'success') {
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
    
    toast.innerHTML = `
        <i class="fa-solid ${icon} toast-icon"></i>
        <span style="font-weight: 500; font-size: 0.95rem;">${message}</span>
    `;
    
    container.appendChild(toast);
    requestAnimationFrame(() => toast.classList.add('show'));

    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 400);
    }, 4000);
}

// 2. Toggle Skeleton/Content
function toggleLoading(isLoading, contentId, skeletonId) {
    const content = document.getElementById(contentId);
    const skeleton = document.getElementById(skeletonId);
    if(isLoading) {
        if(content) content.style.display = 'none';
        if(skeleton) skeleton.style.display = 'grid'; // ou block dependendo do layout
    } else {
        if(skeleton) skeleton.style.display = 'none';
        if(content) content.style.display = 'grid';
    }
}

// 3. Modal Logic
window.abrirModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if(modal) {
        modal.classList.add('show');
        modal.setAttribute('aria-hidden', 'false');
    }
}

window.fecharModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if(modal) {
        modal.classList.remove('show');
        modal.setAttribute('aria-hidden', 'true');
    }
}

// Fecha modal ao clicar fora (Global)
document.addEventListener('click', (e) => {
    if (e.target.classList.contains('modal')) {
        e.target.classList.remove('show');
    }
});