<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sugestão em Análise - ConectaIF</title>
    <style>
        /* CSS REUTILIZADO DO SEU PADRÃO */
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #374151; line-height: 1.6; margin: 0; padding: 0; background-color: #f3f4f6; }
        .container { max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border: 1px solid #e5e7eb; }
        
        /* Cabeçalho (Mantido Verde) */
        .header { background-color: #2E8B57; color: white; padding: 30px 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 600; letter-spacing: 0.5px; }
        .header p { margin: 5px 0 0; font-size: 14px; opacity: 0.9; }

        .content { padding: 30px; }
        .greeting { font-size: 18px; font-weight: 600; color: #111827; margin-bottom: 20px; }
        
        /* Box da Mensagem Original */
        .original-message-box { background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; padding: 15px; margin-bottom: 25px; color: #6b7280; font-size: 0.95rem; font-style: italic; }
        .label-small { font-size: 0.75rem; text-transform: uppercase; color: #9ca3af; font-weight: 700; display: block; margin-bottom: 5px; }

        /* Status Box - AMARELO para indicar processo */
        .status-box { background-color: #fffbeb; border-left: 4px solid #f59e0b; padding: 20px; border-radius: 4px; color: #92400e; margin-bottom: 30px; }
        
        .footer { background-color: #f9fafb; padding: 20px; text-align: center; font-size: 0.8rem; color: #9ca3af; border-top: 1px solid #e5e7eb; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>ConectaIF</h1>
            <p>Sistema de Gestão e Feedback do IFSP</p>
        </div>
        
        <div class="content">
            <div class="greeting">
                Olá, {{ $sugestao->nome ?? 'Estudante' }}.
            </div>
            
            <p>Informamos que sua contribuição foi recebida e encaminhada para o setor responsável.</p>
            
            <div class="original-message-box">
                <span class="label-small">Item em referência (#{{ $sugestao->id }}):</span>
                "{{ \Illuminate\Support\Str::limit($sugestao->conteudo, 200) }}"
            </div>

            <div class="status-box">
                <strong>Status Atual: Em Análise</strong><br>
                <span style="font-size: 0.9rem;">
                    Nossa equipe já visualizou sua demanda e está verificando as informações necessárias para lhe dar um retorno assertivo.
                </span>
            </div>

            <p style="font-size: 0.9rem;">
                Não é necessário responder a este e-mail. Assim que a análise for concluída, você receberá uma notificação com a resposta definitiva.
            </p>
        </div>

        <div class="footer">
            <p>Atenciosamente,<br><strong>Equipe ConectaIF</strong></p>
            <br>
            <p>&copy; {{ date('Y') }} Instituto Federal de São Paulo.</p>
        </div>
    </div>
</body>
</html>