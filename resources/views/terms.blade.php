<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Busca Foto :: Encontre sua foto!</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite('resources/css/landing.css')
    <style>
        main {
            display: flex;
            justify-content: center;
        }

        .terms-container {
            max-width: 1200px;

        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="landing-page">
    <header class="landing-header">
        <div class="header-content">
            <div class="logo-container">
                <a href="/">
                <img src="{{ asset('images/BUSCAFOTO_PRTO.png') }}" alt="Logo Busca Foto" class="logo">
                </a>
            </div>
            <nav class="landing-nav">
                <a href="{{ route('login') }}" class="nav-btn login-btn"><i class="fas fa-sign-in-alt"></i> Entrar</a>
                <a href="{{ route('register') }}" class="nav-btn signup-btn"><i class="fas fa-user-plus"></i> Cadastrar</a>
            </nav>
        </div>
    </header>
    <main class="landing-main">

        <section class="terms-container">
            <h1>TERMO DE USO E CONSENTIMENTO - SISTEMA BUSCA FOTO</h1>
        
            <h2>INTRODUÇÃO</h2>
            <p>Este Termo de Uso e Consentimento ("Termo") estabelece as condições para utilização do sistema Busca Foto ("Sistema"), desenvolvido pelo Professor Vitor Hugo Lopes, hospedado no domínio buscafoto.com.br. O Sistema tem como finalidade principal, mas não exclusiva, o uso acadêmico, permitindo que alunos fotografem pessoas em eventos e disponibilizem essas imagens para que os fotografados possam localizá-las e baixá-las através de um mecanismo de busca por reconhecimento facial.
            </p>
            <p>Ao utilizar o Sistema Busca Foto, você declara ter lido, compreendido e aceitado todos os termos e condições aqui estabelecidos. Caso não concorde com qualquer disposição deste Termo, solicitamos que não utilize o Sistema.
            </p>
            <h2>DEFINIÇÕES</h2>
            <ul>
                <li><strong>"Sistema"</strong> - Plataforma digital Busca Foto, hospedada no domínio buscafoto.com.br;</li>
                <li><strong>"Usuário"</strong> - Pessoa física, maior de 18 anos, que utiliza o Sistema para buscar suas próprias fotografias;</li>
                <li><strong>"Fotografado"</strong> - Pessoa física, maior de 18 anos, que utiliza o Sistema para buscar suas próprias fotografias;</li>
                <li><strong>"Operadores"</strong> - Alunos e professores que compõem a equipe do Busca Foto</li>
                <li><strong>"Dados Pessoais"</strong> - Informações relacionadas à pessoa natural identificada ou identificável, incluindo nome, e-mail, senha e imagens faciais;</li>
                <li><strong>"Tratamento"</strong> - Toda operação realizada com dados pessoais, incluindo coleta, armazenamento, processamento e exclusão;</li>
                <li><strong>"Controlador"</strong> - Equipe composta por professores e alunos, representados na figura do professor Vitor Hugo Lopes, responsáveis pelas decisões referentes ao tratamento;</li>
                <li><strong>"Operador"</strong> - Amazon Web Services (AWS), fornecedor do serviço Amazon Rekognition, que realiza o tratamento de imagens.</li>
            </ul>
        
            <h2>CADASTRO E ACESSO</h2>
            <p>O acesso ao Sistema Busca Foto requer cadastro prévio, com fornecimento de nome completo, endereço de e-mail válido e criação de senha pessoal. Estes dados são necessários para identificação do Usuário e para garantir que apenas as pessoas fotografadas tenham acesso às suas próprias imagens.</p>
            <p>O Usuário é inteiramente responsável pela veracidade das informações fornecidas no cadastro, bem como pela guarda e sigilo de sua senha de acesso. Qualquer atividade realizada com o uso de sua senha será de sua exclusiva responsabilidade.</p>
            <p>O Sistema é destinado exclusivamente a pessoas maiores de 18 anos. Ao se cadastrar, o Usuário declara e garante que possui a idade mínima exigida.</p>
        
            <h2>FUNCIONAMENTO DO SISTEMA</h2>
            <p>O Sistema Busca Foto utiliza a tecnologia Amazon Rekognition para processamento e reconhecimento facial. Ao fazer upload de uma selfie ou capturar uma imagem através do Sistema, esta será processada pelo algoritmo de reconhecimento facial para localizar fotografias nas quais o Usuário aparece.</p>
            <p>O acesso às fotografias é restrito, sendo que cada Usuário poderá visualizar e baixar apenas as imagens nas quais foi identificado pelo sistema de reconhecimento facial. Não é permitido o acesso às fotografias de terceiros.</p>
            <p>Todas as fotografias publicadas no Sistema passam por um processo prévio de moderação realizado pelos Operadores, visando garantir a adequação do conteúdo aos propósitos do Sistema.</p>

            <h2>ARMAZENAMENTO E PRAZO DE RETENÇÃO</h2>
            <p>As fotografias permanecerão disponíveis no Sistema por um período máximo de 10 (dez) dias após o evento em que foram capturadas, sendo automaticamente excluídas após este prazo.</p>
            <p>Os dados cadastrais do Usuário (nome, e-mail e senha) serão mantidos enquanto o cadastro estiver ativo no Sistema, podendo ser excluídos mediante solicitação expressa do Usuário.</p>
            <p>As imagens faciais utilizadas para reconhecimento (selfies) são processadas apenas durante a sessão de busca e não são armazenadas permanentemente nos servidores após o encerramento da sessão.</p>
        
            <h2>CONSENTIMENTO PARA TRATAMENTO DE DADOS PESSOAIS</h2>
            <p>Ao utilizar o Sistema Busca Foto, o Usuário consente expressamente com a coleta, armazenamento, processamento e utilização de seus dados pessoais, incluindo nome, e-mail, senha e imagens faciais (selfie e fotografias), para as finalidades específicas de:</p>
            <ul>
                <li>a) Cadastro e identificação no Sistema;</li>
                <li>b) Processamento de reconhecimento facial para localização de fotografias;</li>
                <li>c) Disponibilização das fotografias para visualização e download pelo próprio Usuário;</li>
                <li>d) Comunicações relacionadas ao funcionamento do Sistema ou serviços relacionados.</li>
            </ul>
            <p>O consentimento para tratamento de dados pessoais, especialmente para o uso de tecnologia de reconhecimento facial, é fornecido de forma livre, informada e inequívoca, podendo ser revogado a qualquer momento pelo Usuário, conforme procedimentos descritos na seção "Direitos do Titular dos Dados".</p>
            <p>O Usuário está ciente de que o não fornecimento do consentimento impossibilitará o acesso ao Sistema e suas funcionalidades, uma vez que o reconhecimento facial é elemento essencial para o funcionamento do serviço.</p>
            <h2>COMPARTILHAMENTO DE DADOS</h2>
            <p>Os dados pessoais coletados pelo Sistema Busca Foto são compartilhados exclusivamente com a Amazon Web Services (AWS), fornecedora do serviço Amazon Rekognition, utilizado para o processamento de reconhecimento facial. Este compartilhamento é necessário para a execução da funcionalidade principal do Sistema.</p>
            <p>Os dados pessoais não serão compartilhados com terceiros, exceto quando exigido por lei ou ordem judicial.</p>
            <h2>MEDIDAS DE SEGURANÇA</h2>
            <p>O Sistema Busca Foto implementa medidas técnicas e administrativas aptas a proteger os dados pessoais de acessos não autorizados e de situações acidentais ou ilícitas de destruição, perda, alteração, comunicação ou difusão, incluindo:</p>
            <ul>
                <li>a) Criptografia de dados sensíveis;</li>
                <li>b) Controle de acesso às informações;</li>
                <li>c) Registro de operações realizadas;</li>
                <li>d) Política de backup e recuperação de dados;</li>
                <li>e) Treinamento dos Operadores quanto às práticas de proteção de dados.</li>
            </ul>
        
            <h2>DIREITOS DO TITULAR DOS DADOS</h2>
            <p>Em conformidade com a Lei Geral de Proteção de Dados (Lei nº 13.709/2018), o Usuário, na qualidade de titular dos dados pessoais, possui os seguintes direitos:</p>
            <ul>
                <li>a) Confirmação da existência de tratamento de seus dados pessoais;</li>
                <li>b) Acesso aos dados pessoais;</li>
                <li>c) Correção de dados incompletos, inexatos ou desatualizados;</li>
                <li>d) Exclusão ou anonimização de dados desnecessários;</li>
                <li>e) Portabilidade dos dados;</li>
                <li>f) Revogação do consentimento a qualquer momento;</li>
                <li>g) Informação sobre a possibilidade de não fornecer consentimento;</li>
                <li>h) Informação sobre entidades públicas e privadas com as quais houve compartilhamento.</li>
            </ul>
            <p>Para exercer esses direitos, envie e-mail para <a href="mailto:contato@buscafoto.com.br">contato@buscafoto.com.br</a>. As solicitações serão processadas no prazo de 15 (quinze) dias.</p>
        
            <h2>REMOÇÃO DE FOTOGRAFIAS</h2>
            <p>O Usuário poderá solicitar a remoção de suas fotografias do Sistema a qualquer momento, mediante envio de e-mail para contato@buscafoto.com.br. A solicitação será processada em até 48 horas úteis, resultando na exclusão definitiva das imagens dos servidores do Sistema.</p>
            <p>A remoção das fotografias não implica necessariamente na exclusão do cadastro do Usuário, que poderá continuar utilizando o Sistema para buscar outras fotografias em eventos futuros, salvo se expressamente solicitada também a exclusão do cadastro.  </p>
            
            <h2>RESPONSABILIDADES</h2>
            <p>O professor Vitor Hugo Lopes e equipe  não se responsabilizam por:</p>
            <ul>
                <li>a) Falhas técnicas ou indisponibilidade temporária do Sistema;</li>
                <li>b) Imprecisões no reconhecimento facial;</li>
                <li>c) Uso indevido das fotografias após download;</li>
                <li>d) Danos decorrentes de caso fortuito, força maior ou ações de terceiros;</li>
                <li>e) Conteúdo das fotografias moderadas;</li>
                <li>f) Informações incorretas fornecidas pelo Usuário.</li>
            </ul>

            <p>O Sistema é fornecido "no estado em que se encontra", sem garantias de qualquer natureza, expressas ou implícitas.</p>

        
            <h2>PROPRIEDADE INTELECTUAL</h2>
            <p>O Sistema Busca Foto, incluindo sua marca, logotipo, estrutura, layout, códigos, software e conteúdo, é de propriedade exclusiva do professor Vitor Hugo Lopes e equipe, sendo protegido pelas leis de propriedade intelectual aplicáveis.</p>
            <p>As fotografias disponibilizadas no Sistema são de propriedade dos respectivos fotógrafos, que autorizam seu uso pessoal pelos Usuários fotografados. Não é permitida a reprodução, distribuição, modificação ou utilização comercial das fotografias sem autorização prévia e expressa.</p>
            <h2>ALTERAÇÕES NO TERMO</h2>
            <p>O presente Termo poderá ser alterado a qualquer momento, sendo as modificações informadas aos Usuários através do próprio Sistema ou por e-mail com antecedência mínima de 30 (trinta) dias. O uso continuado do Sistema após a publicação das alterações implica na aceitação das novas condições.</p>
        
            <h2>DISPOSIÇÕES GERAIS</h2>
            <p>A tolerância quanto ao eventual descumprimento de qualquer das cláusulas deste Termo não constituirá novação ou renúncia dos direitos estabelecidos.
                Caso qualquer disposição deste Termo seja considerada inválida ou inexequível, as demais disposições permanecerão em pleno vigor e efeito.</p>
        
                
        
            <h2>CONTATO</h2>
            <p>Para dúvidas, entre em contato: <a href="mailto:contato@buscafoto.com.br">contato@buscafoto.com.br</a>.</p>
            <p class="text-muted">Data da última atualização: 21 de maio de 2025.</p>
        </section>
        
    </main>
<footer class="app-footer">
    <div class="footer-content">
        <div class="footer-logo">
            <img src="{{ asset('images/unisenac.png') }}" alt="Logo UniSenac">
        </div>

        <div class="footer-links">
            <a href="/terms">Termos de Uso</a>
            <a href="https://senacrs.com.br">SENACRS</a>
            <a href="mailto: contato@buscafoto.com.br">Contato</a>
        </div>
    </div>
</footer>
</body>
</html>
