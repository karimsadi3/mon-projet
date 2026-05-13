@extends('layout')

@section('page_title')
    Liste des <span style="color:#2563eb;">emprunts</span>
@endsection

@section('page_subtitle')
    Suivez les emprunts en cours, les retours et les statuts des livres.
@endsection

@section('content')

    <table>
        <tr>
            <th>ID</th>

            @if(auth()->user()->role === 'admin')
                <th>Utilisateur</th>
            @endif

            <th>Livre</th>
            <th>Date emprunt</th>
            <th>Date retour prévue</th>
            <th>Date retour effective</th>
            <th>Statut</th>
            <th>Action</th>
        </tr>

        @forelse($emprunts as $emprunt)
            <tr>
                <td>{{ $emprunt->id }}</td>

                @if(auth()->user()->role === 'admin')
                    <td>{{ $emprunt->user->name ?? 'Utilisateur' }}</td>
                @endif

                <td>{{ $emprunt->livre->titre ?? '' }}</td>
                <td>{{ \Carbon\Carbon::parse($emprunt->date_emprunt)->format('d/m/Y') }}</td>
                <td>{{ $emprunt->date_retour_prevue }}</td>
                <td>{{ $emprunt->date_retour_effective ?? '-' }}</td>
                <td>
                    @if($emprunt->statut === 'retourne')
                        <span style="
                            background:#dcfce7;
                            color:#166534;
                            padding:6px 10px;
                            border-radius:999px;
                            font-size:13px;
                            font-weight:bold;
                        ">
                            Retourné
                        </span>
                    @else
                        <span style="
                            background:#fef3c7;
                            color:#92400e;
                            padding:6px 10px;
                            border-radius:999px;
                            font-size:13px;
                            font-weight:bold;
                        ">
                            En cours
                        </span>
                    @endif
                </td>
                <td>
                    @if($emprunt->statut !== 'retourne')
                        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'bibliothecaire')
                            <form action="{{ route('retourner', $emprunt->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit">Retourner</button>
                            </form>
                         @else
                            <span style="
                                background:#fee2e2;
                                color:#b91c1c;
                                padding:6px 12px;
                                border-radius:999px;
                                font-size:13px;
                                font-weight:bold;
                            ">
                                Pas encore retourné
                            </span>
                        @endif
                        @else
                         <span style="color:#6b7280;font-weight:bold;">Déjà retourné</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="{{ auth()->user()->role === 'admin' ? 8 : 7 }}" style="text-align:center;color:#6b7280;">
                    Aucun emprunt trouvé.
                </td>
            </tr>
        @endforelse
    </table>

@endsection