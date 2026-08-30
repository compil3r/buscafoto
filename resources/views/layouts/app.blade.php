<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Busca Foto') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800|ibm-plex-mono:400,500,600" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @yield('styles')
</head>
<body class="dashboard-page">
    <header class="app-header bf-header" id="app-header">
        <div class="bf-header-inner">
            <a href="{{ route('dashboard') }}" class="bf-brand">
                <img src="{{ asset('images/BUSCAFOTO_PRTO.png') }}" alt="Busca Foto">
            </a>

            <nav class="bf-nav" id="app-nav">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'is-active' : '' }}"><i class="fas fa-home"></i> Início</a>
                <a href="{{ route('search.form') }}" class="{{ request()->routeIs('search.*') || request()->routeIs('results') ? 'is-active' : '' }}"><i class="fas fa-search"></i> Buscar selfie</a>
                @if(Auth::check() && Auth::user()->isAdmin())
                    <a href="{{ route('gallery') }}" class="{{ request()->routeIs('gallery') ? 'is-active' : '' }}"><i class="fas fa-images"></i> Galeria</a>
                    <a href="{{ route('upload.form') }}" class="{{ request()->routeIs('upload.form') ? 'is-active' : '' }}"><i class="fas fa-upload"></i> Upload</a>
                @endif
            </nav>

            <div class="bf-header-actions">
                @auth
                    <span class="bf-user-name">{{ Auth::user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-outline btn-sm"><i class="fas fa-sign-out-alt"></i> Sair</button>
                    </form>
                @endauth
                <button class="bf-nav-toggle" id="app-nav-toggle" aria-label="Abrir menu" aria-expanded="false">
                    <span></span>
                </button>
            </div>
        </div>
    </header>

    <div class="main-container">
        @if (session('success'))
            <div class="bf-alert bf-alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="bf-alert bf-alert-danger">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="bf-alert bf-alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>

    <footer class="app-footer bf-footer">
        <div class="bf-footer-inner">
            <img src="{{ asset('images/unisenac.png') }}" alt="Logo UniSenac">
        </div>
    </footer>

    <script>
        (function () {
            var toggle = document.getElementById('app-nav-toggle');
            var header = document.getElementById('app-header');
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

    @yield('scripts')

</body>
</html>
