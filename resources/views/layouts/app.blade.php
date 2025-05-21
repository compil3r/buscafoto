<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Busca Foto') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @yield('styles')
</head>
<body class="dashboard-page">
    <header class="app-header">
        <div class="header-content">
            <h1><i class="fas fa-camera-retro"></i> Busca Foto</h1>
            <nav class="main-nav">
                <button class="nav-toggle" aria-label="Abrir menu">
                    <span class="hamburger"></span>
                </button>
                @if(Auth::check() && Auth::user()->isAdmin())
                <ul class="nav-links">
                    <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active-link' : '' }}"><i class="fas fa-home"></i> Início</a></li>
                    
                    <li><a href="{{ route('gallery') }}" class="{{ request()->routeIs('gallery') ? 'active-link' : '' }}"><i class="fas fa-images"></i> Galeria</a></li>
                    
                    <li><a href="{{ route('search.form') }}" class="{{ request()->routeIs('search.form') ? 'active-link' : '' }}"><i class="fas fa-search"></i> Buscar Selfie</a></li>
                   
                        <li><a href="{{ route('upload.form') }}" class="{{ request()->routeIs('upload.form') ? 'active-link' : '' }}"><i class="fas fa-upload"></i> Upload</a></li>
                    
                </ul>
                @endif
            </nav>
            <div id="user-info">
                @auth
                    <span id="user-name">{{ Auth::user()->name }}</span>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-secondary btn-small"><i class="fas fa-sign-out-alt"></i> Sair</button>
                    </form>
                @endauth
            </div>
        </div>
    </header>

    <div class="main-container">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
        
    </div>


    <footer class="app-footer">
        <div class="footer-content">
            <img src="{{ asset('images/unisenac.png') }}" alt="Logo UniSenac">
        </div>
    </footer>

    @yield('scripts')

</body>
</html>
