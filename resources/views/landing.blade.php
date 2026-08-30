<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Busca Foto :: Encontre sua foto!</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Envie uma selfie e encontre automaticamente todas as suas fotos de eventos, com reconhecimento facial.">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800|ibm-plex-mono:400,500,600" rel="stylesheet" />

    @vite('resources/css/landing.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="landing-page">
    <header class="landing-header bf-header" id="landing-header">
        <div class="bf-header-inner">
            <a href="/" class="bf-brand">
                <img src="{{ asset('images/BUSCAFOTO_PRTO.png') }}" alt="Busca Foto">
            </a>
            <nav class="bf-nav" id="landing-nav">
                <a href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> Entrar</a>
                <a href="{{ route('register') }}"><i class="fas fa-user-plus"></i> Cadastrar</a>
            </nav>
            <button class="bf-nav-toggle" id="landing-nav-toggle" aria-label="Abrir menu" aria-expanded="false">
                <span></span>
            </button>
        </div>
    </header>

    <main class="landing-main">

        <section class="hero-section">
            <div class="hero-grid">
                <div class="hero-copy">
                    <span class="eyebrow">Envie 1 selfie e encontre suas fotos</span>
                    <h1 class="display-xl">Sua foto está em algum lugar aí.</h1>
                    <p class="lede">Envie uma selfie e deixe a inteligência artificial vasculhar todas as fotos do evento até encontrar as suas — sem rolar centenas de imagens.</p>
                    <div class="hero-buttons">
                        <a href="{{ route('register') }}" class="btn btn-primary"><i class="fas fa-user-plus"></i> Criar conta</a>
                        <a href="{{ route('login') }}" class="btn btn-outline"><i class="fas fa-sign-in-alt"></i> Já tenho conta</a>
                    </div>
                    <div class="hero-partner">
                        <span>Uma iniciativa do</span>
                        <img src="{{ asset('images/unisenac.png') }}" alt="UniSenac">
                    </div>
                </div>

                <div class="contact-sheet" aria-hidden="true">
                    <div class="sheet-grid">
                        <div class="sheet-frame is-lead af-frame sheet-lock">
                            <span class="match-badge"><i class="fas fa-check"></i> 98.7%</span>
                        </div>
                        <div class="sheet-frame"></div>
                        <div class="sheet-frame"></div>
                        <div class="sheet-frame"></div>
                        <div class="sheet-frame"></div>
                    </div>
                </div>
            </div>
        </section>

        <section class="how-it-works">
            <div class="section-head">
                <span class="eyebrow">O processo</span>
                <h2 class="display-lg">Da câmera até o seu celular</h2>
            </div>
            <div class="process-rail">
                <div class="process-step">
                    <div class="step-head">
                        <div class="card-icon"><i class="fas fa-camera"></i></div>
                        <span class="frame-number">01</span>
                    </div>
                    <h3>Captura</h3>
                    <p>Alunos de Produção Multimídia fotografam o evento em alta qualidade.</p>
                </div>
                <div class="process-step">
                    <div class="step-head">
                        <div class="card-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                        <span class="frame-number">02</span>
                    </div>
                    <h3>Indexação</h3>
                    <p>As imagens são enviadas e cada rosto é indexado pelo Busca Foto.</p>
                </div>
                <div class="process-step">
                    <div class="step-head">
                        <div class="card-icon"><i class="fas fa-search"></i></div>
                        <span class="frame-number">03</span>
                    </div>
                    <h3>Busca</h3>
                    <p>Você envia uma selfie e a IA compara com todo o acervo do evento.</p>
                </div>
                <div class="process-step">
                    <div class="step-head">
                        <div class="card-icon"><i class="fas fa-download"></i></div>
                        <span class="frame-number">04</span>
                    </div>
                    <h3>Download</h3>
                    <p>Visualize as correspondências e baixe, uma a uma ou em lote.</p>
                </div>
            </div>
        </section>

        <section class="about-section">
            <div class="about-content">
                <span class="eyebrow">Sobre o projeto</span>
                <h2 class="display-lg">Uma parceria entre dois cursos do UniSenac</h2>
                <p>Este sistema nasceu da colaboração entre o <strong>CST em Inteligência Artificial e Ciência de Dados</strong> e o <strong>CST em Produção Multimídia</strong>.</p>
                <p>Enquanto um curso registra os momentos do evento com suas câmeras, o outro constrói o sistema inteligente que devolve essas fotos a cada pessoa fotografada.</p>
                <div class="tech-badges">
                    <span class="badge"><i class="fas fa-brain"></i> Inteligência Artificial</span>
                    <span class="badge"><i class="fas fa-camera-retro"></i> Fotografia</span>
                    <span class="badge"><i class="fas fa-code"></i> Desenvolvimento Web</span>
                </div>
            </div>
        </section>

        <section class="cta-section">
            <span class="eyebrow" style="justify-content:center; margin-bottom:14px;">Pronto?</span>
            <h2 class="display-lg">Vamos achar suas fotos</h2>
            <p>Crie sua conta agora e envie sua primeira selfie de busca.</p>
            <a href="{{ route('register') }}" class="btn btn-primary"><i class="fas fa-rocket"></i> Começar agora</a>
        </section>
    </main>

    <footer class="app-footer bf-footer">
        <div class="bf-footer-inner">
            <img src="{{ asset('images/unisenac.png') }}" alt="Logo UniSenac">
            <div class="bf-footer-links">
                <a href="/terms">Termos de Uso</a>
                <a href="https://senacrs.com.br">SENACRS</a>
                <a href="mailto:contato@buscafoto.com.br">Contato</a>
            </div>
            <p class="bf-footer-note">Busca Foto — projeto acadêmico UniSenac</p>
        </div>
    </footer>

    <script>
        (function () {
            var toggle = document.getElementById('landing-nav-toggle');
            var header = document.getElementById('landing-header');
            if (!toggle || !header) return;
            toggle.addEventListener('click', function () {
                var open = header.classList.toggle('is-open');
                toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            });

            var onScroll = function () {
                header.classList.toggle('is-scrolled', window.scrollY > 12);
            };
            onScroll();
            window.addEventListener('scroll', onScroll, { passive: true });
        })();
    </script>
</body>
</html>
