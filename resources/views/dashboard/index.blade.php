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
    <style>
        :root {
            --primary-color: #008000;
            --text: #EDF0F7;
            --sidebar-gray: #45a049;
            --sidebar-gray-light: #F8F7FD;
            --sidebar-gray-background: #45a049;
            --success: #00C896;
            --white: #fff;
        }

        html {
            font-family: Poppins, sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            display: flex;
        }

        nav {
            position: sticky;
            top: 0;
            left: 0;
            height: 100vh;
            background-color: var(--primary-color);
            width: 18rem;
            padding: 0.25rem 0.75rem;
            display: flex;
            color: var(--white);
            flex-direction: column;
            transition: width 0.5s linear;
        }

        body.collapsed nav {
            width: 5rem;
        }

        body.collapsed .hide {
            position: absolute;
            display: none;
            pointer-events: none;
        }

        .sidebar-top {
            position: relative;
            display: flex;
            align-items: start;
            justify-content: center;
            flex-direction: column;
            min-height: 2.5rem;
            padding: 1rem 0;
        }

        body.collapsed .sidebar-top {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .logo__wrapper {
            display: flex;
            justify-content: start;
            align-items: center;
            gap: 1.25rem;
            color: var(--white);
            text-decoration: none;
        }

        .logo {
            width: 3.5rem;
            height: 3.5rem;
            background: white;
            border-radius: 0.75rem;
        }

        .expand-btn {
            top: 1rem;
            right: -4.75rem;
            position: absolute;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            width: 3rem;
            height: 3rem;
            background: var(--white);
            cursor: pointer;
            box-shadow: #6067EB50 0px 2px 8px 0px;
        }

        .expand-btn img {
            transform: rotate(180deg);
            stroke: var(--primary-color);
            width: 2.375rem;
            height: 2.375rem;
        }

        body.collapsed .expand-btn img {
            transform: rotate(360deg);
        }

        .sidebar-links {
            padding: 0.5rem 0;
            border-top: 1px solid var(--sidebar-gray-background);
        }

        .sidebar-links ul {
            list-style-type: none;
            position: relative;
        }

        .sidebar-links li {
            position: relative;
        }

        .sidebar-links li a {
            padding: 0.875rem 0.675rem;
            margin: 0.5rem 0;
            color: var(--sidebar-gray-light);
            font-size: 1.25rem;
            display: flex;
            justify-content: start;
            align-items: center;
            border-radius: 0.675rem;
            height: 3.5rem;
            text-decoration: none;
            transition: all 0.2s ease-in-out;
        }

        .sidebar-links li a img {
            height: 2.125rem;
            width: 2.125rem;
        }

        .sidebar-links .link {
            margin-left: 1.875rem;
        }

        .sidebar-links li a:hover,
        .sidebar-links li a:focus,
        .sidebar-links .active {
            width: 100%;
            text-decoration: none;
            background-color: var(--sidebar-gray-background);
            border-radius: 0.675rem;
            outline: none;
            color: var(--sidebar-gray-light);
        }

        .sidebar-links .active {
            color: var(--white);
        }

        .sidebar-bottom {
            padding: 0.5rem 0;
            display: flex;
            justify-content: center;
            flex-direction: column;
            margin-top: auto;
        }

        .sidebar__profile {
            display: flex;
            align-items: center;
            gap: 1.125rem;
            flex-direction: row;
            padding: 1.5rem 0.125rem;
            border-top: 1px solid var(--sidebar-gray-background);
        }

        .avatar__wrapper {
            position: relative;
            display: flex;
        }

        .avatar {
            display: block;
            width: 3.125rem;
            height: 3.125rem;
            cursor: pointer;
            border-radius: 50%;
            object-fit: cover;
            filter: drop-shadow(
                -20px 0 10px rgba(0, 0, 0, 0.1)
            );
        }

        .avatar:hover {
            transform: scale(1.05);
            transition: all 0.2s ease-in-out;
        }

        .avatar__name {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .user-name {
            font-size: 0.95rem;
            font-weight: 800;
            text-align: left;
        }

        .email {
            font-size: 0.9rem;
        }

        .online__status {
            position: absolute;
            width: 0.75rem;
            height: 0.75rem;
            border-radius: 50%;
            background-color: var(--success);
            bottom: 0.1875rem;
            right: 0.1875rem;
        }

        .tooltip {
            position: relative;
        }

        .tooltip .tooltip__content {
            visibility: hidden;
            background-color: var(--sidebar-gray-background);
            color: var(--white);
            text-align: center;
            border-radius: 0.375rem;
            padding: 0.375rem 0.75rem;
            position: absolute;
            z-index: 1;
            left: 4.6875rem;
        }

        body.collapsed .tooltip:hover .tooltip__content,
        body.collapsed .tooltip:focus .tooltip__content {
            visibility: visible;
        }

        main {
            flex: 1;
            padding: 2rem;
            background: #eef1f7;
            overflow: auto;
        }

        .dashboard {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 1rem;
        }

        .card {
            flex: 1 1 calc(33.333% - 1rem);
            background: #fff;
            padding: 1rem;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: all 0.2s ease-in-out;
        }

        .card:hover {
            transform: translateY(-0.25rem);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }

        .card-h2 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }

        .card-a{
          text-decoration: none;
        }

        .card-p{
          text-decoration: none;
          color: black;

        }

        .tip{
          display: flex;
          align-items: center;
        }


        .idea-img {
  margin-right: 1rem; /* Ajuste conforme necessário */
  width: 50px; /* Ajuste conforme necessário */
  height: auto; /* Mantém a proporção da imagem */
  cursor: pointer;
  transition: all 0.2s ease-in-out;

}

        .idea-img:hover {
  transform: scale(1.1);
}



.wlcm_name {
  color: var(--primary-color);
}

.welcome-sub {
  margin-top: 0.7rem;
  font-size: 1rem;
  color: #333;
  font-family: "Krub", serif;
  font-weight: 400;
  font-style: italic;
}




      

.div-wlcm {
  display: flex;
  align-items: center; /* Alinha o texto e o ícone verticalmente */
  justify-content: space-between; /* Coloca o texto no centro e o ícone no canto direito */
  gap: 1rem; /* Espaçamento entre o texto e o ícone */
  width: 100%; /* Garante que o container ocupe toda a largura */
}

.welcome {
  font-size: 2rem;
  color: #333;
  text-align: center;
  flex-grow: 1; /* Faz com que o texto ocupe o espaço disponível */
}

.clock-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 4.9rem; /* Ajuste o tamanho conforme necessário */
  height: 2.7rem; /* Ajuste o tamanho conforme necessário */
  background-color: white;
  border-radius: 17px; /* Borda arredondada */
  padding: 1rem; /* Espaçamento interno */
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  font-family: "Krub", serif;
  font-weight:500;
  font-size: 1rem;
  color: #333;
  font-style: italic;
  
}

#chart-container {
    width: 100%;
    height: 400px; /* Ajuste conforme necessário */
    margin: 0 auto;
}

canvas {
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
.clock {
  width: 1.8rem; /* Ajuste o tamanho do ícone conforme necessário */
  height: auto;
  margin-right: 0.2rem; /* Ajuste conforme necessário */
}

.chart-container {
  display: flex;
  justify-content: center;
  align-items: center;
  margin: auto;
  margin-top: 5rem;
}



/* Responsividade */
@media (max-width: 768px) {
  .card {
    flex: 1 1 100%;
  }
  .welcome {
    display: none;
  }

  .nav{
    display: none;
  }
}

    </style>
</head>
<body>
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
            <a href="#dashboard" title="Dashboard" class="tooltip">
              <img src="{{asset('assets/review.svg')}}" alt="Dashboard">
              <span class="link hide">Avaliações</span>
              <span class="tooltip__content">Avaliações</span>
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
    <main>
      <header>
  <div class="div-wlcm">
    <h1 class="welcome">{{ $greeting }}, <span class="wlcm_name"> {{ Auth::user()->name }}!</span></h1>
    <div class="clock-btn">
      <img class="clock" src="{{asset('assets/clock.svg')}}" alt="clock">
      <div class="hours" id="hours"></div>

    </div>
  </div>
</header>

    
      <div class="tip">
        <img class="idea-img" src="{{asset('assets/idea.svg')}}" alt="ideia">
      <h2 class="welcome-sub">Nesta área, você pode acessar feedbacks, enviar e-mails, alterar configurações, adicionar novos usuários e excluir usuários existentes. Aproveite todas as funcionalidades disponíveis para gerenciar sua aplicação de forma eficiente e segura.</h2>
      </div>
        <div class="dashboard">
            <div class="card">
              <a class="card-a" href="#">
                <h2 class="card-h2">Avaliações</h2>
                <p class="card-p">Ver avaliações</p>
              </a>
            </div>
            <div class="card">
              <a class="card-a" href="#">
              <h2 class="card-h2">Críticas</h2>
              <p class="card-p"> Ver críticas</p>
            </a>
            </div>
            <div class="card">
              <a class="card-a" href="#">
              <h2 class="card-h2">Sugestões</h2>
              <p class="card-p">Ver sugestões</p>
            </a>
            </div>
        </div>
        {{-- @include('dashboard.report-chart') --}}

        {{-- <div style="width: 50%; margin: auto;">
          {!! $chart->container() !!}
      </div>
  
      {!! $chart->script() !!} --}}
      <div class="chart-container">
      <img src="{{'assets/image.png'}}" alt="">
      </div>
    </main>
    <script>
        const expandBtn = document.querySelector('.expand-btn');
        const body = document.querySelector('body');

        expandBtn.addEventListener('click', () => {
            body.classList.toggle('collapsed');
        });

       function updateClock() {
            var now = new Date();
            var hours = now.getHours();
            var minutes = now.getMinutes();
            minutes = minutes < 10 ? '0' + minutes : minutes;
            var timeString = hours + ':' + minutes;
            document.getElementById('hours').textContent = timeString;
        }

        // Atualiza o relógio a cada segundo
        setInterval(updateClock, 1000);
        // Inicializa o relógio imediatamente
        updateClock();
    </script>
</body>
</html>
