<!-- resources/views/report_chart.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Relatório de Críticas, Feedbacks e Sugestões</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        #chart-container {
    width: 100%;
    height: 400px; /* Ajuste conforme necessário */
    margin: 0 auto;
}

canvas {
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
    </style>
        
</head>
<body>
    <div style="width: 50%; margin: auto;">
        {!! $chart->container() !!}
    </div>

    {!! $chart->script() !!}
</body>
</html>
