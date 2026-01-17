@include('dashboard.includes.sidebar')
<main class="main-content">
  {{-- @include('dashboard.includes.header') --}}
  
  <div class="container dashboard-container">
    <!-- Seção de boas-vindas com design aprimorado -->
    <section class="welcome-section">
      <div class="welcome-card">
        <div class="welcome-icon">
          <img src="{{asset('assets/idea.svg')}}" alt="Ideia">
        </div>
        <div class="welcome-content">
          <h2>Bem-vindo ao Dashboard, <span class="user-welcome">{{ Auth::user()->name }}</span></h2>
          <p>Nesta área, você pode acessar feedbacks, enviar e-mails, alterar configurações, adicionar novos usuários e excluir usuários existentes. Aproveite todas as funcionalidades disponíveis.</p>
          <button class="btn-get-started">Começar <i class="fas fa-arrow-right"></i></button>
        </div>
      </div>
    </section>

    <!-- Cards com animações e melhor organização -->
    <section class="quick-access-section">
      <h2 class="section-title">Acesso Rápido</h2>
      <div class="cards-grid">
        <div class="feature-card">
          <div class="card-icon-wrapper ratings">
            <i class="fas fa-star"></i>
          </div>
          <div class="card-content">
            <h3>Avaliações</h3>
            <p>Visualize e gerencie avaliações dos usuários</p>
            <div class="card-stats">
              <span class="stats-number">24</span>
              <span class="stats-label">novas</span>
            </div>
          </div>
          <a href="#" class="card-action">Ver detalhes <i class="fas fa-chevron-right"></i></a>
        </div>
        
        <div class="feature-card">
          <div class="card-icon-wrapper reviews">
            <i class="fas fa-comments"></i>
          </div>
          <div class="card-content">
            <h3>Críticas</h3>
            <p>Acesse as críticas e feedback dos usuários</p>
            <div class="card-stats">
              <span class="stats-number">12</span>
              <span class="stats-label">novas</span>
            </div>
          </div>
          <a href="#" class="card-action">Ver detalhes <i class="fas fa-chevron-right"></i></a>
        </div>
        
        <div class="feature-card">
          <div class="card-icon-wrapper suggestions">
            <i class="fas fa-lightbulb"></i>
          </div>
          <div class="card-content">
            <h3>Sugestões</h3>
            <p>Veja sugestões de melhorias dos usuários</p>
            <div class="card-stats">
              <span class="stats-number">8</span>
              <span class="stats-label">novas</span>
            </div>
          </div>
          <a href="#" class="card-action">Ver detalhes <i class="fas fa-chevron-right"></i></a>
        </div>
      </div>
    </section>

    <!-- Seção de estatísticas com design moderno -->
    <div class="dashboard-grid-layout">
      <section class="stats-section">
        <div class="stats-header">
          <h2 class="section-title">Estatísticas</h2>
          <div class="stats-actions">
            <select class="time-selector">
              <option>Últimos 7 dias</option>
              <option>Últimos 30 dias</option>
              <option>Este ano</option>
            </select>
            <button class="btn-refresh"><i class="fas fa-sync-alt"></i></button>
          </div>
        </div>
        <div class="chart-wrapper">
          <img src="{{asset('assets/image.png')}}" alt="Estatísticas" class="responsive-chart">
        </div>
      </section>

      <!-- Nova seção de atividades recentes -->
      <section class="recent-activity">
        <h2 class="section-title">Atividades Recentes</h2>
        <ul class="activity-list">
          <li class="activity-item">
            <div class="activity-icon"><i class="fas fa-user-plus"></i></div>
            <div class="activity-details">
              <p class="activity-text">Novo usuário registrado</p>
              <span class="activity-time">Há 2 horas</span>
            </div>
          </li>
          <li class="activity-item">
            <div class="activity-icon"><i class="fas fa-star"></i></div>
            <div class="activity-details">
              <p class="activity-text">Nova avaliação recebida</p>
              <span class="activity-time">Há 4 horas</span>
            </div>
          </li>
          <li class="activity-item">
            <div class="activity-icon"><i class="fas fa-cog"></i></div>
            <div class="activity-details">
              <p class="activity-text">Configurações atualizadas</p>
              <span class="activity-time">Há 1 dia</span>
            </div>
          </li>
        </ul>
        <a href="#" class="view-all">Ver todas atividades</a>
      </section>
    </div>
  </div>
</main>

<!-- Font Awesome para ícones modernos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
