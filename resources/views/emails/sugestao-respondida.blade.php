<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resposta ConectaIF</title>
    <style>
        /* Reset e Fontes */
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #374151; line-height: 1.6; margin: 0; padding: 0; background-color: #f3f4f6; }
        
        /* Container Principal */
        .container { max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border: 1px solid #e5e7eb; }
        
        /* Cabeçalho */
        .header { background-color: #2E8B57; color: white; padding: 30px 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 600; letter-spacing: 0.5px; }
        .header p { margin: 5px 0 0; font-size: 14px; opacity: 0.9; }

        /* Conteúdo */
        .content { padding: 30px; }
        .greeting { font-size: 18px; font-weight: 600; color: #111827; margin-bottom: 20px; }
        
        /* Bloco da Mensagem Original (Contexto) */
        .original-message-box { background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; padding: 15px; margin-bottom: 25px; color: #6b7280; font-size: 0.95rem; font-style: italic; }
        .label-small { font-size: 0.75rem; text-transform: uppercase; color: #9ca3af; font-weight: 700; display: block; margin-bottom: 5px; }

        /* Bloco da Resposta (Destaque) */
        .response-box { background-color: #ecfdf5; border-left: 4px solid #2E8B57; padding: 20px; border-radius: 4px; color: #064e3b; margin-bottom: 30px; }
        
        /* Seção de Alternativas/Ajuda */
        .help-section { border-top: 1px solid #e5e7eb; padding-top: 20px; margin-top: 20px; font-size: 0.9rem; color: #4b5563; }
        .help-section a { color: #2E8B57; text-decoration: none; font-weight: 600; }
        
        /* Rodapé */
        .footer { background-color: #f9fafb; padding: 20px; text-align: center; font-size: 0.8rem; color: #9ca3af; border-top: 1px solid #e5e7eb; }
        .signature-line { margin-bottom: 10px; color: #6b7280; }
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
                Prezado(a) {{ $sugestao->nome ?? 'Estudante' }},
            </div>
            
            <p>Esperamos que este e-mail o(a) encontre bem.</p>
            
            <p>Gostaríamos de agradecer imensamente por sua contribuição. A participação ativa dos alunos é fundamental para a construção de um ambiente acadêmico cada vez melhor no IFSP.</p>
            
            <div class="original-message-box">
                <span class="label-small">Sua sugestão:</span>
                "{{ $sugestao->conteudo }}"
            </div>

            <p>Após análise cuidadosa de nossa equipe, apresentamos o seguinte retorno:</p>
            
            <div class="response-box">
                <span class="label-small" style="color: #2E8B57;">Parecer da Equipe:</span><br>
                {!! nl2br(e($sugestao->conteudo_resposta)) !!}
            </div>

            <div class="help-section">
                <strong>Este retorno não foi suficiente?</strong><br>
                <p style="margin-top: 5px; font-size: 0.85rem;">
                    Nosso objetivo é sempre resolver as questões da melhor forma possível. Caso sinta que sua demanda não foi totalmente atendida ou se tiver novas informações, você pode:
                </p>
                <ul style="font-size: 0.85rem; padding-left: 20px;">
                    <li>Entrar em contato diretamente com a <strong>Coordenação de Curso</strong>.</li>
                    <li>Utilizar o canal oficial da <a href="#">Ouvidoria do IFSP</a> para uma segunda instância de análise.</li>
                    <li>Enviar uma nova sugestão com mais detalhes através do painel do ConectaIF.</li>
                </ul>
            </div>
        </div>

        <div class="footer">
            <div class="signature-line">
                Atendimento realizado por <strong>{{ $sugestao->respondido_por }}</strong><br>
                em {{ $sugestao->data_resposta->format('d/m/Y') }} às {{ $sugestao->data_resposta->format('H:i') }}.
            </div>
            
            <p>Esta é uma mensagem automática enviada pelo sistema <strong>CONECTAIF</strong>.<br>
            Por favor, não responda diretamente a este e-mail.</p>
            
            <p>&copy; {{ date('Y') }} Instituto Federal de São Paulo - Todos os direitos reservados.</p>
        </div>
    </div>
</body>
</html>