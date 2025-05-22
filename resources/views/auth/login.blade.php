<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Busca Foto</title>
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
        .login-box {
            background: var(--white);
            padding: 40px 30px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-lg);
            width: 100%;
            max-width: 400px;
        }
        .login-box h2 {
            text-align: center;
            margin-bottom: 10px;
            color: var(--primary-color);
        }
        .login-box p {
            text-align: center;
            color: var(--dark-gray);
            margin-bottom: 30px;
        }
        .login-box .form-group {
            margin-bottom: 20px;
        }
        .login-box label {
            font-weight: 600;
            color: var(--primary-color);
            display: block;
            margin-bottom: 8px;
        }
        .login-box input[type="email"],
        .login-box input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
        }
        .login-box button {
            width: 100%;
            margin-top: 15px;
        }
        .login-box .links {
            text-align: center;
            margin-top: 20px;
        }
        .login-box .links a {
            display: block;
            color: var(--secondary-color);
            font-weight: 500;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2><i class="fas fa-sign-in-alt"></i> Login</h2>
        <p>Entre com suas credenciais para acessar o sistema</p>

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

        @if (session('status'))
            <div class="status-message success">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Entrar</button>
        </form>

        <div class="links">
            <a href="{{ route('register') }}">Não tem uma conta? Cadastre-se</a>
            <a href="{{ route('password.request') }}">Esqueceu sua senha?</a>
        </div>
    </div>
</body>
</html>
