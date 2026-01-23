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
    <link rel="stylesheet" href="{{ asset('css/responder.css') }}">

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
                <a href="{{route('dashboard')}}" class="nav-link">
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
                        <a href="{{ route('dashboard.sugestoes') }}" class="nav-link">
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
    // --- LÓGICA DE ESTADO (Executar imediatamente para evitar "piscar" a tela) ---
    // Verifica o localStorage. Se não existir, define como 'true' (fechado por padrão).
    const savedState = localStorage.getItem('sidebar-collapsed');
    const body = document.querySelector('body');
    
    // Se o valor for 'true' OU se for nulo (primeira visita), adiciona a classe collapsed
    if (savedState === 'true' || savedState === null) {
        body.classList.add('collapsed');
    } else {
        body.classList.remove('collapsed');
    }

    document.addEventListener('DOMContentLoaded', () => {
        const sidebar = document.querySelector('.sidebar');
        const toggleBtn = document.querySelector('.toggle-btn');
        const hamburgerBtn = document.querySelector('.hamburger-menu');
        const overlay = document.querySelector('.overlay');

        // --- 1. Toggle Desktop com Persistência ---
        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                body.classList.toggle('collapsed');
                
                // Salva o estado atual no localStorage
                const isCollapsed = body.classList.contains('collapsed');
                localStorage.setItem('sidebar-collapsed', isCollapsed);
            });
        }
        function toggleSidebar() {
  document.body.classList.toggle('collapsed');
  
  // Se estiver fechando a sidebar, fecha todos os submenus para evitar bugs visuais
  if (document.body.classList.contains('collapsed')) {
     const openSubmenus = document.querySelectorAll('.has-submenu.open');
     openSubmenus.forEach(el => el.classList.remove('open'));
  }
}

        // --- 2. Toggle Mobile ---
        function toggleMobileMenu() {
            sidebar.classList.toggle('mobile-open'); 
            overlay.classList.toggle('active');
        }

        if (hamburgerBtn) {
            hamburgerBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                toggleMobileMenu();
            });
        }

        if (overlay) {
            overlay.addEventListener('click', () => {
                sidebar.classList.remove('mobile-open');
                overlay.classList.remove('active');
            });
        }

        // --- 3. Submenus ---
        const submenuToggles = document.querySelectorAll('.submenu-toggle');
        submenuToggles.forEach(toggle => {
            toggle.addEventListener('click', (e) => {
                // Impede navegação se for apenas um toggle
                e.preventDefault(); 
                const parent = toggle.parentElement;
                parent.classList.toggle('open');
            });
        });

        // --- 4. Active Link Highlight (Destaque da Página Atual) ---
        const currentUrl = window.location.href;
        const navLinks = document.querySelectorAll('.nav-link');

        navLinks.forEach(link => {
            // Verifica se o href do link é igual a URL atual
            if (link.href === currentUrl) {
                // Adiciona active ao pai (li.nav-item)
                const navItem = link.closest('.nav-item');
                if (navItem) {
                    navItem.classList.add('active');

                    // Se estiver dentro de um submenu, precisamos abrir o pai e ativar o pai também
                    const parentSubmenu = navItem.closest('.submenu');
                    if (parentSubmenu) {
                        const parentNavItem = parentSubmenu.closest('.nav-item');
                        if (parentNavItem) {
                            parentNavItem.classList.add('open'); // Abre o accordion
                            parentNavItem.classList.add('active'); // Opcional: destaca o pai
                        }
                    }
                }
            }
        });
    });
</script>
</body>
</html>