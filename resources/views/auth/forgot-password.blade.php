<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha - Busca Foto</title>
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
        .password-box {
            background: var(--white);
            padding: 40px 30px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-lg);
            width: 100%;
            max-width: 400px;
        }
        .password-box h2 {
            text-align: center;
            margin-bottom: 10px;
            color: var(--primary-color);
        }
        .password-box p {
            text-align: center;
            color: var(--dark-gray);
            margin-bottom: 30px;
            font-size: 0.95rem;
        }
        .password-box .form-group {
            margin-bottom: 20px;
        }
        .password-box label {
            font-weight: 600;
            color: var(--primary-color);
            display: block;
            margin-bottom: 8px;
        }
        .password-box input[type="email"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
        }
        .password-box button {
            width: 100%;
            margin-top: 15px;
        }
        .password-box .links {
            text-align: center;
            margin-top: 20px;
        }
        .password-box .links a {
            display: block;
            color: var(--secondary-color);
            font-weight: 500;
            margin-top: 5px;
        }
        .status-message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="password-box">
        <h2><i class="fas fa-unlock-alt"></i> Recuperar Senha</h2>
        <p>Informe o seu email e enviaremos um link para redefinir sua senha.</p>

        @if (session('status'))
            <div class="status-message success">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <span style="color: #c00; font-size: 0.9rem;">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Enviar Link de Redefinição</button>
        </form>

        <div class="links">
            <a href="{{ route('login') }}">Voltar para o login</a>
        </div>
    </div>
</body>
</html>
