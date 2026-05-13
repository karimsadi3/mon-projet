<x-guest-layout>
    <h2>Inscription</h2>
    <small>Créez votre compte pour accéder à la bibliothèque</small>

    @if ($errors->any())
        <div class="auth-error">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <label for="name">Nom complet</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>

        <label for="email">Adresse email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required>

        <label for="password">Mot de passe</label>
        <input id="password" type="password" name="password" required>

        <label for="password_confirmation">Confirmer le mot de passe</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required>

        <button type="submit" class="btn-submit">Créer un compte</button>

        <div class="auth-links">
            <p>Déjà inscrit ? <a href="{{ route('login') }}">Se connecter</a></p>
        </div>
    </form>
</x-guest-layout>