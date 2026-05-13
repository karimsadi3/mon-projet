<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>La Fureur de Lire</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f6f8fc;
            color: #1f2937;
        }

        .topbar {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 18px 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .brand {
            font-size: 26px;
            font-weight: bold;
            color: #2563eb;
        }

        .topbar-right a {
            text-decoration: none;
            margin-left: 12px;
            padding: 10px 18px;
            border-radius: 10px;
            font-weight: bold;
            font-size: 14px;
        }

        .btn-outline {
            border: 1px solid #d1d5db;
            color: #1f2937;
            background: white;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
        }

        .hero {
            min-height: calc(100vh - 80px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 50px 30px;
        }

        .hero-content {
            max-width: 1200px;
            width: 100%;
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 60px;
            align-items: center;
        }

        .hero-left h1 {
            font-size: 54px;
            line-height: 1.1;
            margin-bottom: 20px;
            color: #111827;
        }

        .hero-left h1 span {
            color: #2563eb;
        }

        .hero-left p {
            font-size: 18px;
            color: #6b7280;
            line-height: 1.7;
            margin-bottom: 30px;
            max-width: 650px;
        }

        .hero-card {
            background: white;
            border-radius: 20px;
            padding: 35px;
            box-shadow: 0 18px 45px rgba(0,0,0,0.08);
            border: 1px solid #eef2f7;
        }

        .hero-card h2 {
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 28px;
            color: #111827;
        }

        .hero-card small {
            display: block;
            margin-bottom: 20px;
            color: #6b7280;
            font-size: 14px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            color: #1f2937;
        }

        input {
            width: 100%;
            padding: 14px 15px;
            border: 1px solid #d1d5db;
            border-radius: 12px;
            margin-bottom: 16px;
            font-size: 15px;
            background: #f9fafb;
        }

        input:focus {
            outline: none;
            border-color: #2563eb;
            background: white;
            box-shadow: 0 0 0 4px rgba(37,99,235,0.10);
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 12px;
            background: #2563eb;
            color: white;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-submit:hover {
            background: #1d4ed8;
        }

        .auth-links {
            text-align: center;
            margin-top: 18px;
            font-size: 14px;
            color: #6b7280;
        }

        .auth-links a {
            color: #2563eb;
            text-decoration: none;
            font-weight: bold;
        }

        .auth-links a:hover {
            text-decoration: underline;
        }

        .auth-error {
            background: #fee2e2;
            color: #b91c1c;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 16px;
            font-size: 14px;
        }

        .auth-status {
            background: #dcfce7;
            color: #166534;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 16px;
            font-size: 14px;
        }

        .hero-badge {
            display: inline-block;
            background: #dbeafe;
            color: #1d4ed8;
            padding: 8px 14px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .hero-list {
            margin-top: 20px;
            color: #6b7280;
            font-size: 15px;
        }

        .hero-list div {
            margin-bottom: 10px;
        }

        @media (max-width: 900px) {
            .topbar {
                padding: 16px 20px;
            }

            .hero-content {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .hero-left h1 {
                font-size: 38px;
            }

            .hero-left p {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>

    <div class="topbar">
        <div class="brand">La Fureur de Lire</div>

        <div class="topbar-right">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-primary">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn-outline">Se connecter</a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-primary">Créer un compte</a>
                    @endif
                @endauth
            @endif
        </div>
    </div>

    <div class="hero">
        <div class="hero-content">
            <div class="hero-left">
                <div class="hero-badge">Bibliothèque moderne</div>

                <h1>
                 Gérez vos <span>livres</span>, vos emprunts et vos lectures simplement
                </h1>

                <p>
                     Une plateforme moderne pour consulter les livres disponibles,
                        suivre vos emprunts et rester informé en toute simplicité.
                </p>

                <div class="hero-list">
                    <div>📚 Consulter les livres disponibles</div>
                    <div>⏳ Suivre vos emprunts</div>
                    <div>🔔 Recevoir des notifications</div>
                    <div>👤 Accéder à votre espace personnel</div>
                </div>
            </div>

            <div class="hero-card">
                {{ $slot }}
            </div>
        </div>
    </div>

</body>
</html>