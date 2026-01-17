<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Dashboard Sidebar</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    <style>
    </style>
</head>
<body>

    <div class="overlay"></div>

    <div class="hamburger-menu">
        <i class="ph ph-list" style="font-size: 24px;"></i>
    </div>

    <nav class="sidebar">
        <div class="sidebar-header">
            <a href="#" class="logo-wrapper">
                <img src="{{ asset('assets/ifsp_logo_itp.png') }}" class="logo-img" alt="Logo">
                <span class="logo-text">IFSP Dashboard</span>
            </a>
            <div class="toggle-btn">
                <i class="ph ph-caret-left"></i>
            </div>
        </div>

        <ul class="nav-list">
            
            <li class="nav-item">
                <a href="{{route('dashboard.sugestoes')}}" class="nav-link">
                    <span class="nav-icon"><i class="ph ph-squares-four"></i></span>
                    <span class="nav-text">Dashboard</span>
                    <span class="tooltip">Dashboard</span>
                </a>
            </li>

            <li class="nav-item has-submenu">
                <a href="#" class="nav-link submenu-toggle">
                    <span class="nav-icon"><i class="ph ph-chart-bar"></i></span>
                    <span class="nav-text">Gestão</span>
                    <i class="ph ph-caret-down chevron"></i> <span class="tooltip">Gestão</span>
                </a>
                <ul class="submenu">
                    <li>
                        <a href="#" class="nav-link">
                            <span class="nav-icon"><i class="ph ph-thumbs-up"></i></span>
                            <span class="nav-text">Sugestões</span>
                        </a>
                    </li>
                    <li>
                        <a href="#project" class="nav-link">
                            <span class="nav-icon"><i class="ph ph-warning-circle"></i></span>
                            <span class="nav-text">Críticas</span>
                        </a>
                    </li>
                    <li>
                        <a href="#performance" class="nav-link">
                            <span class="nav-icon"><i class="ph ph-trend-up"></i></span>
                            <span class="nav-text">Performance</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                <a href="#funds" class="nav-link">
                    <span class="nav-icon"><i class="ph ph-wallet"></i></span>
                    <span class="nav-text">Financeiro</span>
                    <span class="tooltip">Financeiro</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="#settings" class="nav-link">
                    <span class="nav-icon"><i class="ph ph-gear"></i></span>
                    <span class="nav-text">Configurações</span>
                    <span class="tooltip">Configurações</span>
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            <div class="user-profile">
                <img src="{{ asset('assets/admin_panel.svg') }}" class="avatar" alt="Avatar">
                <div class="user-info">
                    <div class="user-name">{{ Auth::check() ? Auth::user()->name : 'Usuário' }}</div>
                    <div class="user-email">{{ Auth::check() ? Auth::user()->email : 'user@email.com' }}</div>
                </div>
                <div class="logout-btn" title="Sair">
                    <i class="ph ph-sign-out" style="font-size: 20px;"></i>
                </div>
            </div>
        </div>
    </nav>


    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const body = document.querySelector('body');
        const sidebar = document.querySelector('.sidebar');
        const toggleBtn = document.querySelector('.toggle-btn');
        const hamburgerBtn = document.querySelector('.hamburger-menu'); // O botão do celular
        const overlay = document.querySelector('.overlay'); // O fundo escuro
        const mainContent = document.querySelector('main'); // Se existir

        // --- 1. Toggle Desktop (A setinha) ---
        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                body.classList.toggle('collapsed');
            });
        }

        // --- 2. Toggle Mobile (CORREÇÃO CRÍTICA AQUI) ---
        function toggleMobileMenu() {
            // AQUI ESTAVA O ERRO: Precisamos adicionar a classe NA SIDEBAR
            sidebar.classList.toggle('mobile-open'); 
            
            // E adicionar a classe no overlay
            overlay.classList.toggle('active');
        }

        // Adiciona o evento de clique ao botão hambúrguer
        if (hamburgerBtn) {
            hamburgerBtn.addEventListener('click', (e) => {
                e.stopPropagation(); // Previne cliques duplos indesejados
                toggleMobileMenu();
            });
        }

        // Fecha ao clicar no fundo escuro
        if (overlay) {
            overlay.addEventListener('click', () => {
                sidebar.classList.remove('mobile-open');
                overlay.classList.remove('active');
            });
        }

        // --- 3. Submenus (Mantenha seu código de submenu aqui) ---
        const submenuToggles = document.querySelectorAll('.submenu-toggle');
        submenuToggles.forEach(toggle => {
            toggle.addEventListener('click', (e) => {
                // ... sua lógica de submenu ...
                const parent = toggle.parentElement;
                parent.classList.toggle('open');
            });
        });
    });
</script>
</body>
</html>