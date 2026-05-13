@extends('layout')

@section('page_title')
    Dashboard <span>Admin</span>
@endsection

@section('page_subtitle')
    Vue globale de la bibliothèque : utilisateurs, livres, emprunts, retards et pénalités.
@endsection

@section('content')
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px;margin-bottom:30px;">
        <div style="background:#eff6ff;padding:22px;border-radius:18px;">
            <h3>👤 Utilisateurs</h3>
            <p style="font-size:30px;font-weight:bold;">{{ $nombreUtilisateurs }}</p>
        </div>

        <div style="background:#ecfdf5;padding:22px;border-radius:18px;">
            <h3>📚 Livres disponibles</h3>
            <p style="font-size:30px;font-weight:bold;">{{ $nombreLivres }}</p>
        </div>

        <div style="background:#fefce8;padding:22px;border-radius:18px;">
            <h3>📖 Livres empruntés</h3>
            <p style="font-size:30px;font-weight:bold;">{{ $livresEmpruntes }}</p>
        </div>

        <div style="background:#fff7ed;padding:22px;border-radius:18px;">
            <h3>⏳ À retourner</h3>
            <p style="font-size:30px;font-weight:bold;">{{ $livresARetourner }}</p>
        </div>

        <div style="background:#fef2f2;padding:22px;border-radius:18px;">
            <h3>⚠️ Retards</h3>
            <p style="font-size:30px;font-weight:bold;">{{ $retards }}</p>
        </div>

        <div style="background:#faf5ff;padding:22px;border-radius:18px;">
            <h3>💰 Total pénalités</h3>
            <p style="font-size:30px;font-weight:bold;">{{ $totalPenalites }} €</p>
        </div>
    </div>

    <h2 style="margin-top:0;">Pénalités non payées par utilisateur</h2>

    <table>
        <tr>
            <th>Utilisateur</th>
            <th>Total pénalité</th>
        </tr>

        @foreach($penalitesParUser as $p)
            <tr>
                <td>{{ $p->user->name ?? 'Inconnu' }}</td>
                <td>{{ $p->total }} €</td>
            </tr>
        @endforeach
    </table>
@endsection