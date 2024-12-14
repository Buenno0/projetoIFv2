<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sugestão Recebida</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            color: #333333;
            margin: 0;
            padding: 0;
        }
        .email-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
        }
        .header {
            text-align: center;
            padding: 20px;
        }
        .header img {
            width: 150px; /* Logo size */
            margin-bottom: 20px;
        }
        h1 {
            color: #008000;
            font-size: 24px;
        }
        p {
            font-size: 16px;
            line-height: 1.6;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #888888;
        }
        .footer a {
            color: #008000;
            text-decoration: none;
        }
        .details {
            background-color: #f1f1f1;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .details p {
            margin: 5px 0;
        }
        .highlight {
            color: #008000;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="email-container">
    <div class="header">
        <img src="https://raw.githubusercontent.com/Buenno0/projetoIFv2/refs/heads/main/public/assets/ifsp_logo_itp.png" alt="Logo">
        <h1>Obrigado por sua sugestão!</h1>
    </div>

    <p>Sua sugestão foi recebida com sucesso. Aqui estão os detalhes:</p>

    <div class="details">
        @if ($sugestao->nome)
            <p><span class="highlight">Nome:</span> {{ $sugestao->nome }}</p>
            @else
            <p><span class="highlight">Nome:</span> Anônimo</p>
        @endif
        <p><span class="highlight">E-mail:</span> {{ $sugestao->email }}</p>
        <p><span class="highlight">Conteúdo:</span> {{ $sugestao->conteudo }}</p>
    </div>

    <p>Agradecemos a sua participação!</p>

    <div class="footer">
        <p>Se você tiver mais alguma dúvida, entre em contato conosco.</p>
        <p><a href="#">Visite nosso site</a></p>
    </div>
</div>
</body>
</html>
