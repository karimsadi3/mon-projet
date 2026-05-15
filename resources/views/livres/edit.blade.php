@extends('layout')

@section('content')

<h1>Modifier un livre</h1>

<form action="{{ route('livres.update', $livre->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <label>Titre :</label>
    <input type="text" name="titre" value="{{ $livre->titre }}">

    <label>Résumé :</label>
    <textarea name="resume">{{ $livre->resume }}</textarea>

    <label>Année de publication :</label>
    <input type="number" name="annee_publication" value="{{ $livre->annee_publication }}">

    <label>Stock :</label>
    <input type="number" name="stock" value="{{ $livre->stock }}">

    <label>Auteur :</label>
    <select name="auteur_id">
        @foreach($auteurs as $auteur)
            <option value="{{ $auteur->id }}" {{ $livre->auteur_id == $auteur->id ? 'selected' : '' }}>
                {{ $auteur->nom }} {{ $auteur->prenom }}
            </option>
        @endforeach
    </select>

    <label>Catégorie :</label>
    <select name="categorie_id">
        @foreach($categories as $categorie)
            <option value="{{ $categorie->id }}" {{ $livre->categorie_id == $categorie->id ? 'selected' : '' }}>
                {{ $categorie->nom }}
            </option>
        @endforeach
    </select>

    <label>Image :</label>
    <input type="file" name="image">

    <br><br>

    <button type="submit">Mettre à jour</button>
</form>

<style>

h1{
    text-align:center;
    color:#1f3c88;
    margin-bottom:35px;
    font-size:36px;
}

form{
    max-width:650px;
    margin:auto;
    background:white;
    padding:35px;
    border-radius:18px;
    box-shadow:0 8px 25px rgba(0,0,0,0.08);
}

label{
    display:block;
    margin-bottom:8px;
    font-weight:bold;
    color:#374151;
}

input[type="text"],
input[type="number"],
textarea,
select{
    width:100%;
    padding:12px;
    border:1px solid #d1d5db;
    border-radius:10px;
    margin-bottom:20px;
    font-size:15px;
    box-sizing:border-box;
}

textarea{
    height:120px;
    resize:none;
}

button{
    width:100%;
    background:#1f3c88;
    color:white;
    border:none;
    padding:14px;
    border-radius:12px;
    font-size:16px;
    font-weight:bold;
    cursor:pointer;
    transition:0.3s;
}

button:hover{
    background:#162d66;
}

</style>

@endsection