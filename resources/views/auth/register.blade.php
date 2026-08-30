<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Busca Foto</title>

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
            <span class="eyebrow" style="justify-content:center;">Primeira vez por aqui</span>
            <h1>Cadastro</h1>
            <p class="auth-subtitle">Crie sua conta para começar a buscar suas fotos.</p>

            @if ($errors->any())
                <div class="bf-alert bf-alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="field">
                    <label for="name">Nome completo</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                </div>
                <div class="field">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                </div>
                <div class="field">
                    <label for="phone">Telefone (opcional)</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}" placeholder="(00) 00000-0000">
                </div>
                <div class="field">
                    <label for="password">Senha</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="field">
                    <label for="password_confirmation">Confirmar senha</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required>
                </div>

                <div class="field-check">
                    <input type="checkbox" id="accepts_marketing" name="accepts_marketing" {{ old('accepts_marketing') ? 'checked' : '' }}>
                    <label for="accepts_marketing" style="margin:0;">Aceito receber contatos de marketing</label>
                </div>
                <div class="field-check">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms" style="margin:0;">Eu li e aceito os <a href="{{ url('terms') }}" target="_blank">Termos de Uso</a></label>
                </div>

                <button type="submit" class="btn btn-primary btn-block auth-submit"><i class="fas fa-user-plus"></i> Cadastrar</button>
            </form>

            <div class="auth-links">
                <a href="{{ route('login') }}">Já tem uma conta? Faça login</a>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const phoneInput = document.getElementById('phone');

        phoneInput.addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '').substring(0, 11);

            if (value.length > 0) {
                value = '(' + value;
            }
            if (value.length > 3) {
                value = value.slice(0, 3) + ') ' + value.slice(3);
            }
            if (value.length > 10 && value.length <= 15) {
                value = value.slice(0, 10) + '-' + value.slice(10);
            } else if (value.length > 9) {
                value = value.slice(0, 9) + '-' + value.slice(9);
            }

            e.target.value = value;
        });
    });
    </script>
</body>
</html>
