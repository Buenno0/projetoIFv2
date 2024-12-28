<header>
    <div class="div-wlcm">
      <h1 class="welcome">{{ $greeting }}, <span class="wlcm_name"> {{ Auth::user()->name }}!</span></h1>
      <div class="clock-btn">
        <img class="clock" src="{{asset('assets/clock.svg')}}" alt="clock">
        <div class="hours" id="hours"></div>
  
      </div>
    </div>
  </header>
  <script>
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