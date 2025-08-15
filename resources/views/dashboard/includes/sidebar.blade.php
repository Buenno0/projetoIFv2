<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Sidebar with Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Krub:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600;1,700&family=Raleway:ital,wght@0,100..900;1,100..900&family=Roboto+Slab:wght@100..900&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body class="collapsed">
  <div class="sidebar-overlay"></div>
  <div class="hamburger-menu">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M3 12H21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      <path d="M3 6H21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      <path d="M3 18H21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
    </svg>
  </div>
  
  <nav>
    <div class="sidebar-top">
      <a href="#" class="logo__wrapper">
        <img src="{{ asset('assets/ifsp_logo_itp.png') }}" class="logo" alt="IFSP">
        <h1 class="hide">IFSP</h1>
      </a>
      
      <div class="expand-btn">
        <img src="{{asset('assets/arrw_right.svg')}}" alt="Chevron">
      </div>
    </div>
    <div class="sidebar-links">
        <ul>
          <li>
            <a href="{{route('dashboard.sugestoes')}}" title="Dashboard" class="tooltip">
              <img src="{{asset('assets/review.svg')}}" alt="Dashboard">
              <span class="link hide">Sugestões</span>
              <span class="tooltip__content">Sugestões</span>
            </a>
          </li>
          <li>
            <a href="#project" title="Project" class="tooltip">
                <img src="{{asset('assets/review.svg')}}" alt="Dashboard">
              <span class="link hide">Críticas</span>
              <span class="tooltip__content">Críticas</span>
            </a>
          </li>
          <li>
            <a href="#performance" title="Performance" class="tooltip">
                <img src="{{asset('assets/review.svg')}}" alt="Dashboard">
              <span class="link hide">Performance</span>
              <span class="tooltip__content">Performance</span>
            </a>
          </li>
        </ul>
      </div>
      <div class="sidebar-bottom">
        <div class="sidebar-links">
          <ul>
            <li>
              <a href="#help" title="Help" class="tooltip">
               <img src="{{ asset('assets/help.svg') }}" alt="Help">
                <span class="link hide">Ajuda</span>
                <span class="tooltip__content">Ajuda</span>
              </a>
            </li>
            <li>
              <a href="#settings" title="Settings" class="tooltip">
                <img src="{{ asset('assets/config.svg') }}" alt="Settings">
                <span class="link hide">Configurações</span>
                <span class="tooltip__content">Configurações</span>
              </a>
            </li>
            <li>
                <a href="#funds" title="Funds" class="tooltip">
                  <img src="{{ asset('assets/account.svg') }}" alt="Funds">
                  <span class="link hide">Conta</span>
                  <span class="tooltip__content">Conta</span>
                </a>
              </li>
          </ul>
        </div>
        <div class="sidebar__profile">
          <div class="avatar__wrapper">
            <img class="avatar" src="{{ asset('assets/admin_panel.svg') }}" alt="Profile">
            <div class="online__status"></div>
          </div>
          <div class="avatar__name hide">
              <div class="user-name">{{Auth::user()->name}}</div>
              <div class="email">{{Auth::user()->email}}</div>
          </div>
        </div>
        {{-- <p>LOgout</p> --}}
      </div>
  </nav>
  <script>
   

//    document.addEventListener('DOMContentLoaded', function() {
//   const expandBtn = document.querySelector('.expand-btn');
//   const body = document.querySelector('body');
  
//   // Toggle da sidebar
//   expandBtn.addEventListener('click', () => {
//     body.classList.toggle('collapsed');
//   });
  
//   // Função para ajustar automaticamente em telas menores
//   function checkScreenSize() {
//     if (window.innerWidth <= 768) {
//       body.classList.add('collapsed');
//     }
//   }
  
//   // Verificar tamanho ao redimensionar
//   window.addEventListener('resize', checkScreenSize);
// });

document.addEventListener('DOMContentLoaded', function() {
  const expandBtn = document.querySelector('.expand-btn');
  const body = document.querySelector('body');
  const hamburgerMenu = document.querySelector('.hamburger-menu');
  
  // Toggle da sidebar no desktop
  expandBtn.addEventListener('click', () => {
    body.classList.toggle('collapsed');
  });
  
  // Toggle da sidebar no mobile
  hamburgerMenu.addEventListener('click', () => {
    body.classList.toggle('sidebar-open');
  });
  
  
  // Função para ajustar automaticamente em telas menores
  function checkScreenSize() {
    if (window.innerWidth <= 768) {
      body.classList.add('collapsed');
      body.classList.remove('sidebar-open');
    }
  }
  // Adicione ao seu JavaScript
const overlay = document.querySelector('.sidebar-overlay');
overlay.addEventListener('click', () => {
  body.classList.remove('sidebar-open');
});

  
  // Fechar sidebar ao clicar em um link (para mobile)
  const sidebarLinks = document.querySelectorAll('.sidebar-links a');
  sidebarLinks.forEach(link => {
    link.addEventListener('click', () => {
      if (window.innerWidth <= 768) {
        body.classList.remove('sidebar-open');
      }
    });
  });
  
  // Verificar tamanho ao redimensionar
  window.addEventListener('resize', checkScreenSize);
  
  // Verificar tamanho inicial
  checkScreenSize();
});





  </script>