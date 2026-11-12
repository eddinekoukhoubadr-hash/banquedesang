<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Banque de Sang</title>
    <link rel="icon" type="image/png" href="{{ asset('icon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa; /* 🔹 Fond blanc cassé */
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background: #fff;
            border-radius: 16px;
            width: 400px;
            padding: 40px 35px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            text-align: center;
            animation: fadeIn 0.6s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-container img {
            width: 90px;
            margin-bottom: 15px;
        }

        .login-container h2 {
            color: #b30000;
            font-weight: 700;
            font-size: 1.6rem;
            margin-bottom: 5px;
        }

        .login-container p {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 25px;
        }

        label {
            display: block;
            text-align: left;
            font-size: 0.9rem;
            color: #333;
            margin-bottom: 5px;
            font-weight: 500;
        }

        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-bottom: 15px;
            transition: border-color 0.3s ease;
        }

        input[type="email"]:focus, input[type="password"]:focus {
            border-color: #b30000;
            outline: none;
            box-shadow: 0 0 5px rgba(179, 0, 0, 0.3);
        }

        .remember-me {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            margin-bottom: 15px;
            font-size: 0.85rem;
            color: #555;
        }

        .remember-me input {
            margin-right: 8px;
        }

        button {
            background-color: #b30000;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 10px 0;
            width: 100%;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #8b0000;
        }

        a {
            color: #b30000;
            text-decoration: none;
            font-size: 0.85rem;
        }

        a:hover {
            text-decoration: underline;
        }

        .footer {
            margin-top: 20px;
            font-size: 0.8rem;
            color: #888;
        }
    </style>
</head>
<body>

<div class="login-container">
    <a href="/"><img src="{{ asset('icon.png') }}" alt="Logo Banque de Sang"></a>
    <h2>Banque de Sang</h2>
    <p>Authentification sécurisée</p>

    @if (session('status'))
        <div style="color: green; font-size: 0.9rem; margin-bottom: 10px;">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <label for="email">Adresse Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="exemple@banque.com" required autofocus>
        @error('email')
            <small style="color: red;">{{ $message }}</small>
        @enderror

        <label for="password">Mot de passe</label>
        <input id="password" type="password" name="password" required placeholder="••••••••">
        @error('password')
            <small style="color: red;">{{ $message }}</small>
        @enderror

        <div class="remember-me">
            <input type="checkbox" id="remember_me" name="remember">
            <label for="remember_me">Se souvenir de moi</label>
        </div>

        <button type="submit">SE CONNECTER</button>
    </form>

    <div class="footer">
        © {{ date('Y') }} Banque de Sang
    </div>
</div>

</body>
</html>
