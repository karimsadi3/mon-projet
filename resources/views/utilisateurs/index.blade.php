@extends('layout')

@section('page_title')
    Gestion des <span style="color:#2563eb;">utilisateurs</span>
@endsection

@section('page_subtitle')
    Consultez, ajoutez et supprimez les utilisateurs de la bibliothèque.
@endsection

@section('content')

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:15px;">
        <h2 style="margin:0;">Liste des utilisateurs</h2>

        <a href="{{ route('utilisateurs.create') }}" style="
            display:inline-block;
            background:#1f3c88;
            color:white;
            padding:12px 18px;
            border-radius:14px;
            text-decoration:none;
            font-weight:bold;
        ">
            + Ajouter un utilisateur
        </a>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Action</th>
        </tr>

        @forelse($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <span style="
                        padding:6px 10px;
                        border-radius:999px;
                        font-size:13px;
                        font-weight:bold;
                        background:
                            {{ $user->role === 'admin' ? '#dbeafe' : ($user->role === 'bibliothecaire' ? '#fef3c7' : '#dcfce7') }};
                        color:
                            {{ $user->role === 'admin' ? '#1d4ed8' : ($user->role === 'bibliothecaire' ? '#92400e' : '#166534') }};
                    ">
                        {{ $user->role }}
                    </span>
                </td>
                <td>
                    @if(auth()->id() !== $user->id)
                        <form action="{{ route('utilisateurs.destroy', $user->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Supprimer cet utilisateur ?')">Supprimer</button>
                        </form>
                    @else
                        <span style="color:#6b7280;font-weight:bold;">Compte connecté</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" style="text-align:center;color:#6b7280;">Aucun utilisateur trouvé.</td>
            </tr>
        @endforelse
    </table>

@endsection