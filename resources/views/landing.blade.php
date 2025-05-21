<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Busca Foto :: Encontre sua foto!</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite('resources/css/landing.css')
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
        
        <section class="hero-section">
            <div class="container">
                <div class="hero-content">
                    <h1>Encontre suas fotos com Inteligência Artificial</h1>
                    <p class="hero-subtitle">Uma parceria entre o Curso Superior de Inteligência Artificial e Ciência de Dados e o Curso Superior de Produção Multimídia do UniSenac</p>
                    <div class="hero-buttons">
                        <a href="{{ route('register') }}" class="btn btn-primary btn-large"><i class="fas fa-user-plus"></i> Criar Conta</a>
                        <a href="{{ route('login') }}" class="btn btn-secondary btn-large"><i class="fas fa-sign-in-alt"></i> Já tenho conta</a>
                    </div>
                </div>
                <div class="hero-image">
                    <img src="{{ asset('images/buscandofoto.png') }}" alt="Ilustração de Busca Foto" class="hero-img">
                </div>
            </div>
        </section>

        <section class="how-it-works">
            <h2>Como Funciona</h2>
            <div class="cards-container">
                <div class="info-card">
                    <div class="card-icon"><i class="fas fa-camera"></i></div>
                    <h3>Captura de Fotos</h3>
                    <p>Os alunos de Produção Multimídia capturam fotos de alta qualidade durante os eventos.</p>
                </div>
                <div class="info-card">
                    <div class="card-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                    <h3>Upload e Indexação</h3>
                    <p>As imagens são enviadas para o sistema e indexadas pela tecnologia do Busca Foto.</p>
                </div>
                <div class="info-card">
                    <div class="card-icon"><i class="fas fa-search"></i></div>
                    <h3>Busca por Selfie</h3>
                    <p>Envie uma selfie e nossa IA encontrará todas as fotos em que você aparece nos eventos.</p>
                </div>
                <div class="info-card">
                    <div class="card-icon"><i class="fas fa-download"></i></div>
                    <h3>Download Fácil</h3>
                    <p>Visualize e baixe suas fotos individualmente ou em grupo com apenas alguns cliques.</p>
                </div>
            </div>
        </section>

        <section class="about-section">
            <div class="about-content">
                <h2>Sobre o Projeto</h2>
                <p>Este sistema é resultado de uma colaboração interdisciplinar entre os cursos de <strong>CST em Inteligência Artificial e Ciência de Dados</strong> e <strong>CST em Produção Multimídia</strong> do UniSenac.</p>
                <p>Enquanto os estudantes de Produção Multimídia capturam momentos especiais dos eventos, o curso de IA desenvolveu um sistema inteligente que permite que você encontre facilmente todas as suas fotos.</p>
                <div class="tech-badges">
                    <span class="badge"><i class="fas fa-brain"></i> Inteligência Artificial</span>
                    <span class="badge"><i class="fas fa-camera-retro"></i> Fotografia</span>
                    <span class="badge"><i class="fas fa-code"></i> Desenvolvimento Web</span>
                </div>
            </div>
        </section>

        <section class="cta-section">
            <h2>Pronto para encontrar suas fotos?</h2>
            <p>Crie sua conta agora e comece a usar nossa tecnologia do Busca Foto.</p>
            <a href="{{ route('register') }}" class="btn btn-primary btn-large"><i class="fas fa-rocket"></i> Começar Agora</a>
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
