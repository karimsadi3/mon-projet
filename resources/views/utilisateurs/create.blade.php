@extends('layout')

@section('content')

<h2>Ajouter un utilisateur</h2>

<form action="{{ route('utilisateurs.store') }}" method="POST" style="max-width:600px;margin:auto;">
    @csrf

    <div style="display:flex;flex-direction:column;gap:15px;">

        <div>
            <label>Nom</label>
            <input type="text" name="name" value="{{ old('name') }}" required>
        </div>

        <div>
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div>
            <label>Mot de passe</label>
            <input type="password" name="password" required>
        </div>

        <div>
            <label>Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation" required>
        </div>

        <div>
            <label>Rôle</label>
            <select name="role" required>
                <option value="adherent">Adhérent</option>
                <option value="bibliothecaire">Bibliothécaire</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <button type="submit">Enregistrer</button>

    </div>
</form>
@if ($errors->any())
    <div style="background:#fee2e2;color:#b91c1c;padding:12px 15px;border-radius:10px;margin-bottom:15px;">
        <ul style="margin:0;padding-left:20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@endsection