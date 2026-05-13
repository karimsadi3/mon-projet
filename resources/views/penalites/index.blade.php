@extends('layout')

@section('page_title')
    Liste des <span style="color:#2563eb;">pénalités</span>
@endsection

@section('page_subtitle')
    Consultez les pénalités, leur montant, leur statut de paiement et les actions disponibles.
@endsection

@section('content')

    <table>
        <tr>
            <th>ID</th>
            <th>Utilisateur</th>
            <th>Emprunt</th>
            <th>Montant</th>
            <th>Payée</th>
            <th>Action</th>
        </tr>

        @forelse($penalites as $penalite)
            <tr>
                <td>{{ $penalite->id }}</td>
                <td>{{ $penalite->user->name ?? '' }}</td>
                <td>{{ $penalite->emprunt->id ?? '' }}</td>
                <td>
                    <span style="font-weight:bold;color:#111827;">
                        {{ $penalite->montant }} €
                    </span>
                </td>
                <td>
                    @if($penalite->payee)
                        <span style="
                            background:#dcfce7;
                            color:#166534;
                            padding:6px 10px;
                            border-radius:999px;
                            font-size:13px;
                            font-weight:bold;
                        ">
                            Oui
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
                            Non
                        </span>
                    @endif
                </td>
                <td>
                    @if(!$penalite->payee)
                        <form action="{{ route('penalites.payer', $penalite->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit">Payer</button>
                        </form>
                    @else
                        <span style="color:#6b7280;font-weight:bold;">Déjà payée</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="text-align:center;color:#6b7280;">
                    Aucune pénalité trouvée.
                </td>
            </tr>
        @endforelse
    </table>

@endsection