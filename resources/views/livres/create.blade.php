@extends('layout')

@section('page_title')
    Ajouter un <span style="color:#2563eb;">livre</span>
@endsection

@section('page_subtitle')
    Remplissez les informations du nouveau livre à ajouter au catalogue.
@endsection

@section('content')

@if ($errors->any())
    <div class="error">
        <ul style="margin:0;padding-left:18px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('livres.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

        {{-- TITRE --}}
        <div style="display:flex;flex-direction:column;gap:6px;">
            <label style="font-weight:600;color:#374151;font-size:14px;">📖 Titre</label>
            <input type="text" name="titre" value="{{ old('titre') }}" placeholder="Ex: Les Misérables">
        </div>

        {{-- ANNÉE --}}
        <div style="display:flex;flex-direction:column;gap:6px;">
            <label style="font-weight:600;color:#374151;font-size:14px;">📅 Année de publication</label>
            <input type="number" name="annee_publication" value="{{ old('annee_publication') }}" placeholder="Ex: 1862">
        </div>

        {{-- AUTEUR --}}
        <div style="display:flex;flex-direction:column;gap:6px;">
            <label style="font-weight:600;color:#374151;font-size:14px;">✍️ Auteur</label>
            <select name="auteur_id" id="select-auteur" onchange="toggleNouvelAuteur(this.value)">
                <option value="">-- Choisir un auteur --</option>
                @foreach($auteurs as $auteur)
                    <option value="{{ $auteur->id }}" {{ old('auteur_id') == $auteur->id ? 'selected' : '' }}>
                        {{ $auteur->nom }} {{ $auteur->prenom }}
                    </option>
                @endforeach
                <option value="nouveau">➕ Ajouter un nouvel auteur</option>
            </select>

            {{-- CHAMPS NOUVEL AUTEUR --}}
            <div id="nouvel-auteur" style="display:none;margin-top:12px;background:#f0f4ff;border:1px solid #c7d2fe;border-radius:12px;padding:16px;gap:10px;flex-direction:column;">
                <p style="font-weight:700;color:#1f3c88;margin:0 0 8px;">👤 Nouvel auteur</p>
                <input type="text" name="nouveau_auteur_nom" placeholder="Nom" style="max-width:100%;">
                <input type="text" name="nouveau_auteur_prenom" placeholder="Prénom" style="max-width:100%;">
            </div>
        </div>

        {{-- CATEGORIE --}}
        <div style="display:flex;flex-direction:column;gap:6px;">
            <label style="font-weight:600;color:#374151;font-size:14px;">🏷️ Catégorie</label>
            <select name="categorie_id">
                <option value="">-- Choisir une catégorie --</option>
                @foreach($categories as $categorie)
                    <option value="{{ $categorie->id }}" {{ old('categorie_id') == $categorie->id ? 'selected' : '' }}>
                        {{ $categorie->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- STOCK --}}
        <div style="display:flex;flex-direction:column;gap:6px;">
            <label style="font-weight:600;color:#374151;font-size:14px;">📦 Stock</label>
            <input type="number" name="stock" value="{{ old('stock') }}" placeholder="Ex: 5" min="0">
        </div>

        {{-- IMAGE --}}
        <div style="display:flex;flex-direction:column;gap:6px;">
            <label style="font-weight:600;color:#374151;font-size:14px;">🖼️ Image</label>
            <input type="file" name="image" accept="image/*" style="padding:10px;">
        </div>

        {{-- RESUME --}}
        <div style="display:flex;flex-direction:column;gap:6px;grid-column:span 2;">
            <label style="font-weight:600;color:#374151;font-size:14px;">📝 Résumé</label>
            <textarea name="resume" rows="5" placeholder="Décrivez le livre en quelques lignes..."
                style="max-width:100%;resize:vertical;">{{ old('resume') }}</textarea>
        </div>

    </div>

    {{-- BOUTONS --}}
    <div style="display:flex;gap:12px;margin-top:24px;">
        <button type="submit">
            ✅ Enregistrer le livre
        </button>
        <a href="{{ route('livres.index') }}" style="
            display:inline-block;
            background:#e5e7eb;
            color:#374151;
            padding:12px 16px;
            border-radius:14px;
            text-decoration:none;
            font-weight:700;
        ">
            ← Annuler
        </a>
    </div>

</form>

<script>
function toggleNouvelAuteur(value) {
    const div = document.getElementById('nouvel-auteur');
    div.style.display = value === 'nouveau' ? 'flex' : 'none';
}
</script>

@endsection