<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar - Busca Foto</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800|ibm-plex-mono:400,500,600" rel="stylesheet" />

    @vite('resources/css/landing.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="auth-page">
    <div class="auth-shell">
        <a href="/" class="auth-brand">
            <img src="{{ asset('images/BUSCAFOTO_PRTO.png') }}" alt="Busca Foto">
        </a>

        <div class="auth-card">
            <span class="eyebrow" style="justify-content:center;">Bem-vindo de volta</span>
            <h1>Entrar</h1>
            <p class="auth-subtitle">Acesse sua conta para buscar suas fotos.</p>

            @if ($errors->any())
                <div class="bf-alert bf-alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('status'))
                <div class="bf-alert bf-alert-success">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="field">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                </div>
                <div class="field">
                    <label for="password">Senha</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block auth-submit"><i class="fas fa-sign-in-alt"></i> Entrar</button>
            </form>

            <div class="auth-links">
                <a href="{{ route('register') }}">Não tem uma conta? Cadastre-se</a>
                <a href="{{ route('password.request') }}">Esqueceu sua senha?</a>
            </div>
        </div>
    </div>
</body>
</html>
