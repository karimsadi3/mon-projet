<!DOCTYPE html>
<html>
<head>
    <title>Bibliothèque - La Fureur de Lire</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- 🔥 AJOUT OBLIGATOIRE POUR LARAVEL AJAX -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Inter, Arial, sans-serif;
            background: #f8f5ec;
            color: #1f2937;
        }

        .app {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 280px;
            background: #ffffff;
            border-right: 1px solid #e5e7eb;
            padding: 28px 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 4px 0 20px rgba(0,0,0,0.04);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 30px;
        }

        .brand-logo {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            background: linear-gradient(135deg, #1f3c88, #3b82f6);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 22px;
            font-weight: bold;
        }

        .brand-text h2 {
            margin: 0;
            font-size: 20px;
            color: #111827;
        }

        .brand-text p {
            margin: 2px 0 0;
            font-size: 13px;
            color: #6b7280;
        }

        .menu-title {
            font-size: 12px;
            font-weight: bold;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 24px 0 12px;
        }

        .nav-links {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .nav-links a,
        .nav-links button {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            border: none;
            background: transparent;
            color: #1f2937;
            padding: 14px 16px;
            border-radius: 16px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.25s ease;
        }

        .nav-links a:hover,
        .nav-links button:hover {
            background: #1f3c88;
            color: white;
            transform: translateX(6px) scale(1.02);
            box-shadow: 0 10px 20px rgba(31,60,136,0.18);
        }

        .user-box {
            margin-top: 28px;
            padding: 18px;
            border-radius: 18px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
        }

        .user-box .name {
            font-weight: 700;
            color: #111827;
            margin-bottom: 4px;
        }

        .user-box .role {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 14px;
        }

        .logout-btn {
            background: #ef4444 !important;
            color: white !important;
            justify-content: center;
        }

        .logout-btn:hover {
            background: #dc2626 !important;
            transform: scale(1.02);
        }

        .main {
            flex: 1;
            padding: 30px;
        }

        .hero {
            background: linear-gradient(135deg, #e8f0ff, #ffffff);
            border: 1px solid #e5e7eb;
            border-radius: 28px;
            padding: 34px;
            margin-bottom: 24px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.05);
        }

        .hero-badge {
            display: inline-block;
            padding: 8px 14px;
            background: #dbeafe;
            color: #1d4ed8;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .hero h1 {
            margin: 0 0 14px;
            font-size: 42px;
            line-height: 1.1;
            color: #111827;
        }

        .hero h1 span {
            color: #2563eb;
        }

        .hero p {
            margin: 0;
            max-width: 760px;
            color: #6b7280;
            font-size: 17px;
            line-height: 1.7;
        }

        .panel {
            background: #ffffff;
            border-radius: 24px;
            padding: 26px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 12px 30px rgba(0,0,0,0.05);
        }

        .success {
            background: #dcfce7;
            color: #166534;
            padding: 14px 16px;
            border-radius: 14px;
            margin-bottom: 18px;
            font-weight: 600;
        }

        .error {
            background: #fee2e2;
            color: #b91c1c;
            padding: 14px 16px;
            border-radius: 14px;
            margin-bottom: 18px;
            font-weight: 600;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 18px;
            overflow: hidden;
            border-radius: 18px;
        }

        th {
            background: #1f3c88;
            color: white;
            padding: 16px;
            text-align: left;
            font-size: 14px;
        }

        td {
            background: white;
            padding: 16px;
            border-bottom: 1px solid #eef2f7;
        }

        tr:hover td {
            background: #f8fbff;
        }

        input, select, textarea {
            width: 100%;
            max-width: 460px;
            padding: 13px 15px;
            border: 1px solid #d1d5db;
            border-radius: 14px;
            margin-bottom: 12px;
            font-size: 14px;
            background: #fcfcfd;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37,99,235,0.10);
            background: white;
        }

        button {
            background: #1f3c88;
            color: white;
            border: none;
            padding: 12px 16px;
            border-radius: 14px;
            cursor: pointer;
            font-weight: 700;
            transition: 0.2s ease;
        }

        button:hover {
            background: #2563eb;
            transform: scale(1.03);
        }

        .action-link {
            display: inline-block;
            margin-right: 12px;
            color: #2563eb;
            font-weight: 700;
            text-decoration: none;
        }

        .action-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 960px) {
            .app {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid #e5e7eb;
            }

            .main {
                padding: 20px;
            }

            .hero h1 {
                font-size: 32px;
            }
        }
    </style>
</head>

<body>

<div class="app">
    <aside class="sidebar">
        <div>
            <div class="brand">
                <div class="brand-logo">📚</div>
                <div class="brand-text">
                    <h2>Bibliothèque</h2>
                    <p>La Fureur de Lire</p>
                </div>
            </div>

            <div class="menu-title">Navigation</div>

            <div class="nav-links">
                @auth
                    <a href="{{ url('/livres') }}">📘 <span>Livres</span></a>
                    <a href="{{ url('/emprunts') }}">📖 <span>Emprunts</span></a>
                    <a href="{{ url('/notifications') }}">🔔 <span>Notifications</span></a>
                    <a href="{{ url('/profile') }}">👤 <span>Profil</span></a>

                    @if(in_array(auth()->user()->role, ['admin', 'bibliothecaire']))
                        <a href="{{ route('relances.index') }}">⚠️ Relances</a>
                        <a href="{{ route('admin.emprunts.create') }}">➕ <span>Nouvel emprunt</span></a>
                        <a href="{{ url('/penalites') }}">💰 <span>Pénalités</span></a>
                        <a href="{{ route('utilisateurs.index') }}">👥 <span>Utilisateurs</span></a>
                    @endif

                    @if(auth()->user()->role === 'admin')
                        <a href="{{ url('/dashboard') }}">📊 <span>Dashboard</span></a>
                    @endif
                @endauth

                @guest
                    <a href="{{ route('login') }}">🔐 <span>Connexion</span></a>
                    <a href="{{ route('register') }}">📝 <span>Inscription</span></a>
                @endguest
            </div>
        </div>

        @auth
        <div class="user-box">
            <div class="name">{{ auth()->user()->name }}</div>
            <div class="role">Rôle : {{ auth()->user()->role }}</div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <div class="nav-links">
                    <button type="submit" class="logout-btn">Déconnexion</button>
                </div>
            </form>
        </div>
        @endauth
    </aside>

    <main class="main">
        <div class="hero">
            <div class="hero-badge">Espace bibliothèque</div>
            <h1>{!! $__env->yieldContent('page_title', 'Bienvenue dans votre espace') !!}</h1>
            <p>@yield('page_subtitle', 'Gérez votre espace de bibliothèque dans une interface moderne, claire et professionnelle.')</p>
        </div>

        <div class="panel">
            @if(session('success'))
                <div class="success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="error">{{ session('error') }}</div>
            @endif

            @yield('content')
        </div>
    </main>
</div>

<!-- 🔥 AJOUT IMPORTANT POUR LES SCRIPTS RELANCE -->
@stack('scripts')

</body>
</html>