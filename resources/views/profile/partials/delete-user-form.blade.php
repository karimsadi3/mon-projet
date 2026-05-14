<section class="space-y-6">

    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            Supprimer le compte
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Attention : cette action est irréversible.
        </p>
    </header>

    <form method="POST" action="{{ route('profile.destroy') }}">
        @csrf
        @method('DELETE')

        <div class="mt-4">
            <input
                type="password"
                name="password"
                placeholder="Confirme ton mot de passe"
                required
                style="padding:10px;border:1px solid #ccc;border-radius:8px;width:300px;"
            >

            @error('password')
                <p style="color:red;font-size:12px;">{{ $message }}</p>
            @enderror
        </div>

        <button
            type="submit"
            onclick="return confirm('Tu es sûr de vouloir supprimer ton compte ?')"
            style="
                margin-top:15px;
                background:#dc2626;
                color:white;
                padding:10px 15px;
                border:none;
                border-radius:8px;
                cursor:pointer;
            "
        >
            Supprimer mon compte.
        </button>

    </form>

</section>