@extends('layout')

@section('page_title')
    Mon <span style="color:#2563eb;">Profil</span>
@endsection

@section('page_subtitle')
    Gérez vos informations personnelles et la sécurité de votre compte.
@endsection

@section('content')

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;flex-wrap:wrap;">

    {{-- INFORMATIONS PERSONNELLES --}}
    <div style="background:#f8fafc;border:1px solid #e5e7eb;border-radius:16px;padding:24px;">
        <h3 style="font-size:18px;font-weight:700;color:#1f2937;margin-bottom:20px;">
            👤 Informations personnelles
        </h3>
        @include('profile.partials.update-profile-information-form')
    </div>

    {{-- MOT DE PASSE --}}
    <div style="background:#f8fafc;border:1px solid #e5e7eb;border-radius:16px;padding:24px;">
        <h3 style="font-size:18px;font-weight:700;color:#1f2937;margin-bottom:20px;">
            🔒 Changer le mot de passe
        </h3>
        @include('profile.partials.update-password-form')
    </div>

    {{-- SUPPRIMER COMPTE --}}
    <div style="background:#fff5f5;border:1px solid #fecaca;border-radius:16px;padding:24px;grid-column:span 2;">
        <h3 style="font-size:18px;font-weight:700;color:#b91c1c;margin-bottom:20px;">
            🗑️ Supprimer le compte
        </h3>
        @include('profile.partials.delete-user-form')
    </div>

</div>

@endsection