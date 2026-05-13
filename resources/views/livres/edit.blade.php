<!DOCTYPE html>
<html>
<head>
    <title>Modifier un livre</title>
</head>
<body>
    <h1>Modifier un livre</h1>

    <form action="{{ route('livres.update', $livre->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label>Titre :</label>
        <input type="text" name="titre" value="{{ $livre->titre }}"><br><br>

        <label>Résumé :</label>
        <textarea name="resume">{{ $livre->resume }}</textarea><br><br>

        <label>Année de publication :</label>
        <input type="number" name="annee_publication" value="{{ $livre->annee_publication }}"><br><br>

        <label>Stock :</label>
        <input type="number" name="stock" value="{{ $livre->stock }}"><br><br>

        <label>Auteur :</label>
        <select name="auteur_id">
            @foreach($auteurs as $auteur)
                <option value="{{ $auteur->id }}" {{ $livre->auteur_id == $auteur->id ? 'selected' : '' }}>
                    {{ $auteur->nom }} {{ $auteur->prenom }}
                </option>
            @endforeach
        </select><br><br>

        <label>Catégorie :</label>
        <select name="categorie_id">
            @foreach($categories as $categorie)
                <option value="{{ $categorie->id }}" {{ $livre->categorie_id == $categorie->id ? 'selected' : '' }}>
                    {{ $categorie->nom }}
                </option>
            @endforeach
        </select><br><br>

        <label>Image :</label>
        <input type="file" name="image"><br><br>

        <button type="submit">Mettre à jour</button>
    </form>
</body>
</html>