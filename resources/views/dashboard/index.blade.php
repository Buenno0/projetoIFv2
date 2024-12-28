@include('dashboard.includes.sidebar')
    <main>
      
      @include('dashboard.includes.header')
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
</body>
</html>
