<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Banque de Sang</title>
    <link rel="icon" type="image/png" href="{{ asset('icon.png') }}">
    <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .card {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 400px;
            width: 90%;
        }
        .card img {
            width: 120px;
            margin-bottom: 20px;
        }
        .title {
            font-size: 1.8rem;
            color: #b30000;
            margin-bottom: 15px;
            font-weight: 700;
        }
        .subtitle {
            color: #555;
            font-size: 0.95rem;
            margin-bottom: 25px;
        }
        .btn {
            display: inline-block;
            background-color: #b30000;
            color: #fff;
            padding: 10px 25px;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #800000;
        }
        footer {
            margin-top: 40px;
            font-size: 0.85rem;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="card">
        <img src="{{ asset('icon.png') }}" alt="Banque de Sang">
        <h1 class="title">Banque de Sang</h1>
        <p class="subtitle">Accédez à votre tableau de bord pour gérer les demandes et le suivi des dons.</p>

        @auth
            <a href="{{ url('/dashboard') }}" class="btn">Accéder au Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="btn">Se connecter</a>
        @endauth
    </div>

    <footer>
        © {{ date('Y') }} Banque De Sang — Tous droits réservés.
    </footer>
</body>
</html>
