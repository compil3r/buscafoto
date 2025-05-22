<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Busca Foto</title>
    @vite('resources/css/landing.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: var(--light-gray);
        }
        .register-box {
            background: var(--white);
            padding: 40px 30px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-lg);
            width: 100%;
            max-width: 500px;
            margin-top: 70px;
        }
        .register-box h2 {
            text-align: center;
            margin-bottom: 10px;
            color: var(--primary-color);
        }
        .register-box p {
            text-align: center;
            color: var(--dark-gray);
            margin-bottom: 30px;
        }
        .register-box .form-group {
            margin-bottom: 20px;
        }
        .register-box label {
            font-weight: 600;
            color: var(--primary-color);
            display: block;
            margin-bottom: 8px;
        }
        .register-box input[type="text"],
        .register-box input[type="email"],
        .register-box input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
        }
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .register-box button {
            width: 100%;
            margin-top: 15px;
        }
        .register-box .links {
            text-align: center;
            margin-top: 20px;
        }
        .register-box .links a {
            display: block;
            color: var(--secondary-color);
            font-weight: 500;
            margin-top: 5px;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-img {
            max-width: 180px;
            height: auto;
        }

    </style>
</head>
<body>
    <div class="register-box">
        <div class="logo-container">
            <img src="{{ asset('images/BUSCAFOTO_PRTO.png') }}" alt="Logo Busca Foto" class="logo-img">
        </div>

        <h2><i class="fas fa-user-plus"></i> Cadastro</h2>
        <p>Crie sua conta para acessar o sistema</p>
        @if ($errors->any())
            <div class="alert" style="background-color: #ffe5e5; color: #721c24; border: 1px solid #f5c6cb; padding: 10px 15px; border-radius: 8px; margin-bottom: 20px;">
                <strong>Erros encontrados:</strong>
                <ul style="margin: 10px 0 0 20px; padding: 0; list-style: disc;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <label for="name">Nome Completo</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label for="phone">Telefone (opcional)</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone') }}" placeholder="(00) 00000-0000">
            </div>
            

            <div class="form-group">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="password_confirmation">Confirmar Senha</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>
            <div class="form-group checkbox-group">
                <input type="checkbox" id="accepts_marketing" name="accepts_marketing" {{ old('accepts_marketing') ? 'checked' : '' }}>
                <label for="accepts_marketing">Aceito receber contatos de marketing</label>
            </div>
        
            <div class="form-group checkbox-group">
                <input type="checkbox" id="terms" name="terms" required>
                <label for="terms">Eu li e aceito os <a href="{{ url('terms') }}" target="_blank">Termos de Uso</a></label>
            </div>
            <button type="submit" class="btn btn-primary">Cadastrar</button>
        </form>

        <div class="links">
            <a href="{{ route('login') }}">Já tem uma conta? Faça login</a>
        </div>
    </div>
</body>
<script>

document.addEventListener('DOMContentLoaded', function () {
    const phoneInput = document.getElementById('phone');

    phoneInput.addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '').substring(0, 11); // Só números, máx 11 dígitos

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

    
</html>
