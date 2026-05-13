<div class="nav-links">
    @auth
        <a href="{{ url('/livres') }}">📘 <span>Livres</span></a>
        <a href="{{ url('/emprunts') }}">📖 <span>Emprunts</span></a>
        <a href="{{ url('/notifications') }}">🔔 <span>Notifications</span></a>
        <a href="{{ url('/profile') }}">👤 <span>Profil</span></a>

        @if(in_array(auth()->user()->role, ['admin', 'bibliothecaire']))
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