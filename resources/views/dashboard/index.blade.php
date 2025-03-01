@include('dashboard.includes.sidebar')
<main class="main-content">
  @include('dashboard.includes.header')
  
  <div class="container">
    <section class="tip-section">
      <div class="tip">
        <div class="tip-icon">
          <img class="idea-img" src="{{asset('assets/idea.svg')}}" alt="ideia">
        </div>
        <div class="tip-content">
          <h2 class="welcome-title">Bem-vindo ao Dashboard</h2>
          <p class="welcome-text">Nesta área, você pode acessar feedbacks, enviar e-mails, alterar configurações, adicionar novos usuários e excluir usuários existentes. Aproveite todas as funcionalidades disponíveis para gerenciar sua aplicação de forma eficiente e segura.</p>
        </div>
      </div>
    </section>

    <section class="cards-section">
      <div class="dashboard-grid">
        <div class="card">
          <a class="card-link" href="#">
            <div class="card-icon">
              <i class="fas fa-star"></i>
            </div>
            <div class="card-content">
              <h2 class="card-title">Avaliações</h2>
              <p class="card-description">Ver avaliações</p>
            </div>
          </a>
        </div>
        
        <div class="card">
          <a class="card-link" href="#">
            <div class="card-icon">
              <i class="fas fa-comments"></i>
            </div>
            <div class="card-content">
              <h2 class="card-title">Críticas</h2>
              <p class="card-description">Ver críticas</p>
            </div>
          </a>
        </div>
        
        <div class="card">
          <a class="card-link" href="#">
            <div class="card-icon">
              <i class="fas fa-lightbulb"></i>
            </div>
            <div class="card-content">
              <h2 class="card-title">Sugestões</h2>
              <p class="card-description">Ver sugestões</p>
            </div>
          </a>
        </div>
      </div>
    </section>

    <section class="chart-section">
      <div class="chart-container">
        <h2 class="section-title">Estatísticas</h2>
        <div class="chart-wrapper">
          <img src="{{asset('assets/image.png')}}" alt="Estatísticas" class="responsive-chart">
        </div>
      </div>
    </section>
  </div>
</main>

<!-- Adicione Font Awesome para ícones modernos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
  :root {
    --primary-color: #B1141F;
    --primary-dark: #8e1019;
    --primary-light: #d41a27;
    --secondary-color: #c62a38;
    --accent-color: #e63946;
    --text-color: #333;
    --light-text: #666;
    --background: #f8f9fa;
    --card-bg: #fff;
    --shadow: 0 4px 12px rgba(177, 20, 31, 0.1);
    --border-radius: 12px;
    --transition: all 0.3s ease;
  }

  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  body {
    font-family: 'Poppins', sans-serif;
    background-color: var(--background);
    color: var(--text-color);
    line-height: 1.6;
  }

  .main-content {
    padding: 20px;
    margin-left: 250px; /* Ajuste conforme a largura da sua sidebar */
    transition: var(--transition);
  }

  .container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px 0;
  }

  /* Tip Section */
  .tip-section {
    margin-bottom: 30px;
  }

  .tip {
    display: flex;
    align-items: center;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: white;
    border-radius: var(--border-radius);
    padding: 25px;
    box-shadow: var(--shadow);
  }

  .tip-icon {
    flex: 0 0 60px;
    margin-right: 20px;
  }

  .idea-img {
    width: 60px;
    height: 60px;
    filter: brightness(0) invert(1);
  }

  .tip-content {
    flex: 1;
  }

  .welcome-title {
    font-size: 1.5rem;
    margin-bottom: 10px;
    font-weight: 600;
  }

  .welcome-text {
    font-size: 1rem;
    opacity: 0.9;
  }

  /* Cards Section */
  .cards-section {
    margin-bottom: 30px;
  }

  .dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
  }

  .card {
    background-color: var(--card-bg);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    transition: var(--transition);
    overflow: hidden;
    border-top: 3px solid var(--primary-color);
  }

  .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 24px rgba(177, 20, 31, 0.15);
  }

  .card-link {
    display: flex;
    flex-direction: column;
    padding: 25px;
    color: var(--text-color);
    text-decoration: none;
    height: 100%;
  }

  .card-icon {
    margin-bottom: 15px;
    font-size: 2rem;
    color: var(--primary-color);
  }

  .card-title {
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 10px;
  }

  .card-description {
    color: var(--light-text);
    font-size: 0.95rem;
  }

  /* Chart Section */
  .chart-section {
    margin-top: 40px;
  }

  .chart-container {
    background-color: var(--card-bg);
    border-radius: var(--border-radius);
    padding: 25px;
    box-shadow: var(--shadow);
    border-left: 3px solid var(--primary-color);
  }

  .section-title {
    font-size: 1.3rem;
    margin-bottom: 20px;
    color: var(--primary-color);
    font-weight: 600;
  }

  .chart-wrapper {
    width: 100%;
    overflow: hidden;
  }

  .responsive-chart {
    width: 100%;
    height: auto;
    display: block;
    border-radius: 8px;
  }

  /* Botões e elementos interativos */
  button, .btn {
    background-color: var(--primary-color);
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 6px;
    cursor: pointer;
    transition: var(--transition);
  }

  button:hover, .btn:hover {
    background-color: var(--primary-dark);
  }

  /* Responsividade */
  @media (max-width: 1024px) {
    .main-content {
      margin-left: 0;
      padding: 15px;
    }
    
    .dashboard-grid {
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    }
  }

  @media (max-width: 768px) {
    .tip {
      flex-direction: column;
      text-align: center;
      padding: 20px;
    }
    
    .tip-icon {
      margin-right: 0;
      margin-bottom: 15px;
    }
    
    .welcome-title {
      font-size: 1.3rem;
    }
    
    .welcome-text {
      font-size: 0.9rem;
    }
    
    .dashboard-grid {
      grid-template-columns: 1fr;
    }
  }

  @media (max-width: 480px) {
    .container {
      padding: 10px;
    }
    
    .card-link {
      padding: 20px;
    }
    
    .chart-container {
      padding: 15px;
    }
    
    .section-title {
      font-size: 1.1rem;
    }
  }
</style>
</body>
</html>
