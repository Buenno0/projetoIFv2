@php
    // Fallback: Se o controller não enviou a variável $greeting, nós calculamos aqui
    if (!isset($greeting)) {
        $hour = now()->hour;
        $greeting = match (true) {
            $hour < 12 => 'Bom dia',
            $hour < 18 => 'Boa tarde',
            default    => 'Boa noite',
        };
    }
@endphp

<header>
    <div class="div-wlcm">
        <h1 class="welcome">
            {{ $greeting }}, 
            <span class="wlcm_name">{{ Auth::user()->name }}!</span>
        </h1>
        
        <div class="clock-btn">
            <i class="fa-regular fa-clock" style="color: var(--primary-color); font-size: 1.2rem;"></i>
            <div class="hours" id="hours"></div>
        </div>
    </div>
</header>

<script>
    function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        document.getElementById('hours').textContent = `${hours}:${minutes}`;
    }

    setInterval(updateClock, 1000);
    updateClock();
</script>