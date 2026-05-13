@extends('layout')

@section('page_title')
    Gestion des <span style="color:#2563eb;">livres</span>
@endsection

@section('page_subtitle')
    Consultez les livres disponibles, recherchez un ouvrage et gérez le catalogue de la bibliothèque.
@endsection

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;gap:20px;flex-wrap:wrap;margin-bottom:25px;">

    @if ($errors->any())
        <div style="background:#fee2e2;color:#b91c1c;padding:15px;border-radius:10px;margin-bottom:20px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="GET" action="{{ route('livres.index') }}" style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;margin:0;">
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Rechercher un livre..."
            style="margin:0;min-width:280px;"
        >
        <button type="submit">Rechercher</button>
    </form>
    <form method="POST"
            action="{{ route('livres.recherche.ia') }}"
            style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;margin-top:15px;">

            @csrf

            <input
                type="text"
                name="prompt"
                placeholder="Recherche intelligente IA..."
                required
                style="margin:0;min-width:280px;"
            >

            <button type="submit"
                style="
                    background:#7c3aed;
                    color:white;
                    border:none;
                    padding:10px 16px;
                    border-radius:10px;
                    cursor:pointer;
                    font-weight:bold;
                ">
                🤖 Recherche intelligente
            </button>

</form>

    @if(auth()->check() && auth()->user()->role === 'admin')
        <a href="{{ route('livres.create') }}" style="
            display:inline-block;
            background:#1f3c88;
            color:white;
            padding:12px 18px;
            border-radius:14px;
            text-decoration:none;
            font-weight:bold;
        ">
            + Ajouter un livre
        </a>
    @endif

</div>
{{-- RESULTAT IA --}}
@if(session('resultatIA'))

    <div style="
        background:white;
        padding:25px;
        border-radius:16px;
        margin-bottom:25px;
        box-shadow:0 4px 20px rgba(0,0,0,0.08);
    ">

        <h2 style="
            color:#117a65;
            margin-bottom:15px;
        ">
            🤖 Résultat de la recherche intelligente
        </h2>

        <p style="
            line-height:1.8;
            color:#374151;
            margin-bottom:20px;
            white-space:pre-line;
        ">
            {{ session('resultatIA') }}
        </p>

        @if(session('livresRecommandes') && count(session('livresRecommandes')) > 0)

            <h3 style="margin-bottom:15px;color:#1f3c88;">
                📚 Livres recommandés
            </h3>

            <div style="
                display:grid;
                grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
                gap:20px;
            ">

                @foreach(session('livresRecommandes') as $livre)

                    <div style="
                        border:1px solid #e5e7eb;
                        border-radius:14px;
                        padding:15px;
                        background:#f9fafb;
                    ">

                        @if($livre->image)
                            <img
                                src="{{ asset('storage/' . $livre->image) }}"
                                style="
                                    width:100%;
                                    height:220px;
                                    object-fit:cover;
                                    border-radius:10px;
                                    margin-bottom:10px;
                                "
                            >
                        @endif

                        <h4 style="
                            font-size:18px;
                            margin-bottom:8px;
                            color:#111827;
                        ">
                            {{ $livre->titre }}
                        </h4>

                        <p style="
                            font-size:14px;
                            color:#6b7280;
                            margin-bottom:10px;
                        ">
                            {{ Str::limit($livre->resume, 120) }}
                        </p>

                        <span style="
                            background:#dcfce7;
                            color:#166534;
                            padding:6px 10px;
                            border-radius:999px;
                            font-size:12px;
                            font-weight:bold;
                        ">
                            {{ $livre->stock }} disponible(s)
                        </span>

                    </div>

                @endforeach

            </div>

        @endif

    </div>

@endif
<table>
    <tr>
        <th>ID</th>
        <th>Titre</th>
        <th>Auteur</th>
        <th>Catégorie</th>
        <th>Stock</th>
        <th>Image</th>
        <th>Actions</th>
    </tr>

    @forelse($livres as $livre)
        <tr>
            <td>{{ $livre->id }}</td>
            <td>{{ $livre->titre }}</td>
            <td>
                {{ $livre->auteur->nom ?? '' }}
                {{ $livre->auteur->prenom ?? '' }}
            </td>
            <td>{{ $livre->categorie->nom ?? '' }}</td>

            <td>
                @if($livre->stock > 0)
                    <span style="
                        background:#dcfce7;
                        color:#166534;
                        padding:6px 10px;
                        border-radius:999px;
                        font-size:13px;
                        font-weight:bold;
                    ">
                        {{ $livre->stock }} disponible(s)
                    </span>
                @else
                    <span style="
                        background:#fee2e2;
                        color:#b91c1c;
                        padding:6px 10px;
                        border-radius:999px;
                        font-size:13px;
                        font-weight:bold;
                    ">
                        Indisponible
                    </span>
                @endif
            </td>

            <td>
                @if($livre->image)
                    <img
                        src="{{ asset('storage/' . $livre->image) }}"
                        alt="Image du livre"
                        style="width:70px;height:90px;object-fit:cover;border-radius:12px;"
                    >
                @else
                    <span style="color:#6b7280;">Aucune image</span>
                @endif
            </td>

            <td>
                <div style="display:flex;gap:10px;flex-wrap:wrap;">

                    {{-- ADMIN ACTIONS --}}
                    @if(auth()->check() && auth()->user()->role === 'admin')

                        <a class="action-link" href="{{ route('livres.edit', $livre->id) }}">
                            Modifier
                        </a>

                        <form action="{{ route('livres.destroy', $livre->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Supprimer ce livre ?')">
                                Supprimer
                            </button>
                        </form>

                    @endif

                    {{-- EMPRUNT --}}
                    @if($livre->stock > 0)
                        @auth
                            <form action="{{ route('emprunter', $livre->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit">Emprunter</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}">
                                Emprunter
                            </a>
                        @endauth
                    @endif

                    {{-- IA --}}
                    <button onclick="afficherResume({{ $livre->id }})"
                        style="background:#6c3483;color:white;border:none;padding:6px 10px;border-radius:4px;cursor:pointer;">
                        Résumé
                    </button>

                    <button onclick="afficherRecommandations({{ $livre->id }})"
                        style="background:#117a65;color:white;border:none;padding:6px 10px;border-radius:4px;cursor:pointer;">
                        💡 Recommandations
                    </button>

                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="7" style="text-align:center;color:#6b7280;">
                Aucun livre trouvé.
            </td>
        </tr>
    @endforelse

</table>

@if(method_exists($livres, 'links'))
    <div style="margin-top:20px;">
        {{ $livres->links() }}
    </div>
@endif

{{-- POPUP IA --}}
<div id="popup-ia" onclick="fermerPopup()" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;
         background:rgba(0,0,0,0.5);z-index:9999;justify-content:center;align-items:center;">
    <div onclick="event.stopPropagation()" style="background:white;padding:30px;border-radius:10px;max-width:600px;width:90%;position:relative;">
        <button onclick="fermerPopup()"
            style="position:absolute;top:10px;right:15px;background:none;border:none;font-size:20px;cursor:pointer;">
            ✖
        </button>

        <h3 id="popup-titre" style="color:#1f3c88;margin-bottom:15px;"></h3>
        <p id="popup-contenu" style="line-height:1.6;">Chargement...</p>
    </div>
</div>

<script>
function afficherResume(id) {
    document.getElementById('popup-ia').style.display = 'flex';
    document.getElementById('popup-titre').innerText = '🤖 Résumé IA';
    document.getElementById('popup-contenu').innerText = 'Chargement...';

    fetch('/api/livres/' + id + '/summary')
        .then(r => r.json())
        .then(data => {
            document.getElementById('popup-titre').innerText = '🤖 ' + data.titre;
            document.getElementById('popup-contenu').innerText = data.resume_ai;
        })
        .catch(() => {
            document.getElementById('popup-contenu').innerText = 'Erreur IA.';
        });
}

function afficherRecommandations(id) {
    document.getElementById('popup-ia').style.display = 'flex';
    document.getElementById('popup-titre').innerText = '💡 Recommandations IA';
    document.getElementById('popup-contenu').innerText = 'Chargement...';

    fetch('/api/livres/' + id + '/recommandations')
        .then(r => r.json())
        .then(data => {
            document.getElementById('popup-titre').innerText = '💡 ' + data.livre_base;
            document.getElementById('popup-contenu').innerText = data.recommandations;
        })
        .catch(() => {
            document.getElementById('popup-contenu').innerText = 'Erreur IA.';
        });
}

function fermerPopup() {
    document.getElementById('popup-ia').style.display = 'none';
}
</script>

@endsection