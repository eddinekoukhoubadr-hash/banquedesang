<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Banque de Sang</title>
    <link rel="icon" type="image/png" href="{{ asset('icon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            display: flex;
            min-height: 100vh;
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
            margin: 0;
        }

        /* 🔹 Sidebar */
        .sidebar {
            width: 250px;
            background-color: #b30000;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            padding: 20px 10px;
            transition: all 0.3s ease;
            z-index: 50;
        }

        .sidebar .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar .logo img {
            width: 110px;
            display: block;
            margin: 0 auto;
        }

        .sidebar nav a,
        .sidebar nav button {
            display: block;
            width: 100%;
            padding: 10px 15px;
            color: #fff;
            font-weight: 500;
            text-decoration: none;
            border-radius: 6px;
            margin-bottom: 10px;
            text-align: left;
            background: none;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .sidebar nav a:hover,
        .sidebar nav button:hover,
        .sidebar nav a.active {
            background-color: #800000;
            transform: translateX(4px);
        }

        .sidebar .footer {
            font-size: 12px;
            text-align: center;
            opacity: 0.8;
            margin-top: 10px;
        }

        /* 🔹 Main content */
        .main-content {
            flex-grow: 1;
            margin-left: 250px;
            padding: 30px;
            overflow-x: auto;
            transition: all 0.3s ease;
        }

        /* 🔹 Mobile menu button */
        .mobile-menu-btn {
            display: none;
            position: fixed;
            top: 10px;
            left: 10px;
            background: #b30000;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 12px;
            font-size: 18px;
            z-index: 100;
        }

        /* 🔹 Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                position: fixed;
                width: 220px;
                height: 100vh;
                top: 0;
                left: 0;
            }

            .sidebar.active {
                transform: translateX(0);
                box-shadow: 2px 0 10px rgba(0, 0, 0, 0.2);
            }

            .mobile-menu-btn {
                display: block;
            }

            .main-content {
                margin-left: 0;
                padding: 20px 15px;
            }
        }
    </style>
</head>
<body>

    <!-- ☰ Bouton menu mobile -->
    <button class="mobile-menu-btn" id="menuToggle">☰</button>

    <!-- 🔴 Barre latérale -->
    <aside class="sidebar" id="sidebar">
        <div>
            <div class="logo">
                <a href="/dashboard">
                <img src="{{ asset('logoBS.png') }}" alt="Banque de Sang">
                <h2 class="text-lg font-semibold mt-2">Banque De Sang</h2>
                </a>
            </div>

            <nav>
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">🏠 Dashboard</a>
                <a href="{{ route('bloodbank.create') }}" class="{{ request()->routeIs('bloodbank.create') ? 'active' : '' }}">➕ Nouvelle Demande</a>
                <a href="{{ route('statistics.index') }}" class="{{ request()->routeIs('statistics.index') ? 'active' : '' }}">📊 Statistiques</a>

                @if(auth()->user()->role === 'admin')
                    <a href="{{ url('/users') }}" class="{{ request()->is('users*') ? 'active' : '' }}">👥 Gestion Utilisateurs</a>
                    <a href="{{ route('settings.index') }}" class="{{ request()->routeIs('settings.*') ? 'active' : '' }}">
                        ⚙️ Gestion Paramètres
                    </a>
                @endif

                <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">👤 Mon Profil</a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">🚪 Se Déconnecter</button>
                </form>
            </nav>
        </div>

        <div class="footer">
            <p>© {{ date('Y') }} Banque De Sang</p>
        </div>
    </aside>

    <!-- 🩸 Contenu principal -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- 📱 Script pour menu mobile -->
    <script>
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');

        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });
    </script>

</body>
</html>
