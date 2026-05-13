<x-guest-layout>
    <h2>Connexion</h2>
    <small>Accédez à votre espace bibliothèque</small>

    @if (session('status'))
        <div class="auth-status">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="auth-error">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <label for="email">Adresse email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>

        <label for="password">Mot de passe</label>
        <input id="password" type="password" name="password" required>

        <div style="display:flex;align-items:center;margin-bottom:16px;">
            <input type="checkbox" name="remember" id="remember" style="width:auto;margin:0 8px 0 0;">
            <label for="remember" style="margin:0;font-weight:normal;">Se souvenir de moi</label>
        </div>

        <button type="submit" class="btn-submit">Se connecter</button>

        <div class="auth-links">
            <p>Pas encore de compte ? <a href="{{ route('register') }}">Créer un compte</a></p>

            @if (Route::has('password.request'))
                <p><a href="{{ route('password.request') }}">Mot de passe oublié ?</a></p>
            @endif
        </div>
    </form>
</x-guest-layout>