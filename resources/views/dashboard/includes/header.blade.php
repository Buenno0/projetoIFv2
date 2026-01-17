@php
    // Mantendo sua lógica de saudação (Fallback)
    if (!isset($greeting)) {
        $hour = now()->hour;
        $greeting = match (true) {
            $hour < 12 => 'Bom dia',
            $hour < 18 => 'Boa tarde',
            default    => 'Boa noite',
        };
    }
@endphp

<header class="dashboard-header">
    <div class="header-content">
        <h1 class="header-title">
            {{ $greeting }}, <span class="header-highlight">{{ Auth::user()->name }}!</span>
        </h1>
    </div>

    <div class="header-clock">
        <i class="far fa-clock clock-icon"></i>
        <span id="digital-clock" class="clock-time">--:--</span>
    </div>
</header>

<script>
    function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        document.getElementById('digital-clock').textContent = `${hours}:${minutes}`;
    }

    // Atualiza imediatamente e depois a cada segundo
    updateClock();
    setInterval(updateClock, 1000);
</script>