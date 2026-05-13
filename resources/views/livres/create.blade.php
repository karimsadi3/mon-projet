<!DOCTYPE html>
<html>
<head>
    <title>Ajouter un livre</title>
</head>
<body>
    <h1>Ajouter un livre</h1>
    @if ($errors->any())
        <div style="background:red;color:white;padding:15px;margin-bottom:20px;">
            <ul>
                 @foreach ($errors->all() as $error)
                 <li>{{ $error }}</li>
                @endforeach
             </ul>
        </div>
    @endif
    <form action="{{ route('livres.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <label>Titre :</label>
        <input type="text" name="titre"><br><br>

        <label>Résumé :</label>
        <textarea name="resume"></textarea><br><br>

        <label>Année de publication :</label>
        <input type="number" name="annee_publication"><br><br>

        <label>Stock :</label>
        <input type="number" name="stock"><br><br>

        <label>Auteur :</label>
        <select name="auteur_id">
            @foreach($auteurs as $auteur)
                <option value="{{ $auteur->id }}">{{ $auteur->nom }} {{ $auteur->prenom }}</option>
            @endforeach
        </select><br><br>

        <label>Catégorie :</label>
        <select name="categorie_id">
            @foreach($categories as $categorie)
                <option value="{{ $categorie->id }}">{{ $categorie->nom }}</option>
            @endforeach
        </select><br><br>

        <label>Image :</label>
        <input type="file" name="image"><br><br>

        <button type="submit">Enregistrer</button>
    </form>
</body>
</html>