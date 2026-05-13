@extends('layout')

@section('content')
    <h1>Créer un emprunt (Admin)</h1>

    <form action="{{ route('admin.emprunts.store') }}" method="POST">
        @csrf

        <label>Choisir un utilisateur :</label>
        <select name="user_id" required>
            <option value="">-- Choisir --</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
            @endforeach
        </select>

        <label>Choisir un livre :</label>
        <select name="livre_id" required>
            <option value="">-- Choisir --</option>
            @foreach($livres as $livre)
                <option value="{{ $livre->id }}">{{ $livre->titre }} (stock: {{ $livre->stock }})</option>
            @endforeach
        </select>

        <button type="submit">Créer l’emprunt</button>
    </form>
@endsection