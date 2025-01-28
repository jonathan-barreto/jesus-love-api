<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Termos e Política de Privacidade - Jesus Love</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background-color: #f4f4f4;
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        header {
            background-color: #6e0000;
            color: white;
            padding: 20px;
            text-align: center;
        }
        header > h1{
            color: white;
            padding: 20px;
            text-align: center;
        }
        .container {
            width: 100%; /* Mudança para 100% da largura */
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 10px; /* Ajuste para não ter padding nas laterais */
            flex: 1;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center; /* Centralizando o título */
            color: #6e0000; 
        }
        h2 {
            font-size: 22px;
            margin-bottom: 15px;
            color: #6e0000;
        }
        p {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 15px;
            color: #4a4a4a;
        }
        ul {
            margin-left: 20px;
            padding: 0;
        }
        ul li {
            list-style-type: none;
            margin-bottom: 10px;
        }
        strong {
            font-weight: bold;
        }
        footer {
            background-color: #6e0000;
            color: white;
            text-align: center;
            padding: 20px 0;
            margin-top: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        footer img {
            max-width: 150px;
            margin-bottom: 10px;
        }
        footer p {
            font-size: 14px;
            margin-bottom: 10px;
            color: white; /* Cor branca para o texto do copyright */
        }
        footer a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            margin-top: 5px;
        }

        /* Responsividade */
        @media (max-width: 1024px) {
            footer {
                padding: 20px;
            }
            footer a {
                font-size: 14px;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 0; /* Remove o padding do body */
            }
            header {
                padding: 10px;
            }
            h1 {
                font-size: 20px;
            }
            h2 {
                font-size: 18px;
            }
            p {
                font-size: 14px;
            }
            .container {
                padding: 0 10px; /* Ajuste para não ter padding nas laterais */
            }
            footer {
                padding: 20px 10px;
            }
            footer img {
                max-width: 120px;
                margin-bottom: 15px;
            }
            footer a {
                margin-top: 10px;
                font-size: 16px;
            }
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 18px;
            }
            h2 {
                font-size: 16px;
            }
            p {
                font-size: 12px;
            }
            footer {
                padding: 15px 10px;
            }
            footer img {
                max-width: 100px;
                margin-bottom: 10px;
            }
            footer p {
                font-size: 12px;
                margin-bottom: 10px;
            }
            footer a {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Termos e Política de Privacidade</h1>
    </header>

    <div class="container">
        <h2>1. Coleta de Informações Pessoais</h2>
        <p>Ao utilizar o aplicativo "Jesus Love", coletamos as seguintes informações pessoais:</p>
        <ul>
            <li><strong>Nome completo</strong>: Coletado por meio de formulário no momento do registro. Usado para exibição no aplicativo.</li>
            <li><strong>E-mail</strong>: Coletado durante o cadastro para permitir o login e o envio de notificações.</li>
            <li><strong>Senha</strong>: Coletada durante o registro para garantir a segurança da sua conta.</li>
            <li><strong>Idade</strong>: Coletada no momento do registro para personalização de conteúdo e compatibilidade de perfis.</li>
            <li><strong>Localização (Latitude e Longitude)</strong>: Coletada automaticamente através de uma API com base em sua requisição. Usada para calcular a distância entre os usuários e otimizar sua experiência no app.</li>
            <li><strong>ID do dispositivo (Device ID)</strong>: Coletado pelo Firebase para envio de notificações e garantir a personalização das mensagens.</li>
            <li><strong>Fotos</strong>: Coletadas por meio de um formulário para personalizar seu perfil e exibição no aplicativo.</li>
        </ul>

        <h2>2. Uso das Informações</h2>
        <p>As informações que coletamos são usadas para os seguintes fins:</p>
        <ul>
            <li><strong>Personalização do conteúdo</strong>: O nome, idade e localização são usados para personalizar a experiência do usuário e facilitar a interação entre perfis compatíveis.</li>
            <li><strong>Cálculo de distância</strong>: A localização (latitude e longitude) é utilizada para calcular a distância entre usuários, permitindo uma experiência mais precisa no app.</li>
            <li><strong>Notificações</strong>: O ID do dispositivo é usado para enviar notificações relevantes sobre novas interações, atualizações de perfil e outras informações importantes.</li>
            <li><strong>Suporte e melhorias</strong>: Usamos as informações para oferecer suporte ao usuário e melhorar continuamente a funcionalidade do aplicativo.</li>
        </ul>

        <h2>3. Compartilhamento de Dados</h2>
        <p>Não compartilhamos suas informações pessoais com terceiros, exceto quando necessário para os seguintes propósitos:</p>
        <ul>
            <li><strong>Prestadores de serviços</strong>: Podemos compartilhar suas informações com empresas que nos ajudam a operar o aplicativo, como provedores de serviços de nuvem ou notificações push (ex.: Firebase).</li>
            <li><strong>Cumprimento de obrigações legais</strong>: Podemos compartilhar suas informações se necessário para cumprir obrigações legais ou para proteger nossos direitos.</li>
        </ul>

        <h2>4. Segurança das Informações</h2>
        <p>Adotamos medidas de segurança razoáveis para proteger suas informações pessoais, incluindo criptografia e controle de acesso. No entanto, nenhuma transmissão de dados pela internet pode ser 100% segura, e não podemos garantir a segurança absoluta.</p>

        <h2>5. Seus Direitos</h2>
        <p>Você tem os seguintes direitos em relação às suas informações pessoais:</p>
        <ul>
            <li><strong>Acesso</strong>: Você pode solicitar uma cópia das informações que coletamos sobre você.</li>
            <li><strong>Correção</strong>: Você pode atualizar ou corrigir qualquer informação pessoal incorreta ou desatualizada.</li>
            <li><strong>Exclusão</strong>: Você pode solicitar a exclusão de suas informações pessoais a qualquer momento, sujeito às exigências legais e contratuais.</li>
            <li><strong>Revogação do consentimento</strong>: Você pode revogar seu consentimento para o uso de algumas informações a qualquer momento.</li>
        </ul>

        <h2>6. Cookies e Tecnologias de Rastreamento</h2>
        <p>O aplicativo "Jesus Love" pode usar cookies e outras tecnologias de rastreamento para melhorar sua experiência. Estes são usados para analisar a interação do usuário com o aplicativo e ajudar na personalização do conteúdo. Você pode configurar seu dispositivo para recusar cookies, embora isso possa afetar a funcionalidade do aplicativo.</p>

        <h2>7. Alterações na Política de Privacidade</h2>
        <p>Esta Política de Privacidade pode ser atualizada periodicamente. Quaisquer alterações serão publicadas neste documento, e a data da última atualização será indicada no topo da página. Recomendamos que você reveja esta política regularmente.</p>
    </div>

    <footer>
        <img src="{{ asset('images/logo_jesus_love_no_bg.png') }}" alt="Logo Jesus Love">
        <p>&copy; 2024 Jesus Love - Todos os direitos reservados.</p>
        <a href="https://www.instagram.com/jesuslove_oficial/" target="_blank" style="display: inline-flex; align-items: center;">
            Instagram
        </a>
    </footer>

</body>
</html>
