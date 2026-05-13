@extends('layout')

@section('content')

<h1 class="notif-title">🔔 Notifications</h1>

@if($notifications->count() > 0)

<!-- Bouton supprimer tout -->
<form action="{{ route('notifications.deleteAll') }}" method="POST">
    @csrf
    @method('DELETE')

    <button class="btn-delete-all">
        🗑️ Tout supprimer
    </button>
</form>

<div class="notif-container">

    @foreach($notifications as $notification)

    <div class="notif-card">

        <div class="notif-header">

            <div>
                <span class="notif-status">
                    {{ $notification->read_at ? '✅ Lu' : '🔵 Non lu' }}
                </span>

                <span class="notif-date">
                    {{ $notification->created_at->format('d/m/Y H:i') }}
                </span>
            </div>

            <!-- Supprimer UNE notification -->
            <form action="{{ route('notifications.delete', $notification->id) }}" method="POST">

                @csrf
                @method('DELETE')

                <button class="btn-delete-one">
                    ❌ Supprimer
                </button>

            </form>

        </div>

        <div class="notif-body">

            <p>
                {{ $notification->data['message'] ?? 'Aucun message' }}
            </p>

            <div class="notif-details">
                <strong>Livre :</strong>
                {{ $notification->data['livre'] ?? '-' }}
            </div>

            <div class="notif-details">
                <strong>Date d'emprunt :</strong>
                {{ $notification->data['date_emprunt'] ?? '-' }}
            </div>

        </div>

    </div>

    @endforeach

</div>

@else

<p class="empty-msg">Aucune notification disponible.</p>

@endif

<style>

.notif-title{
    margin-bottom:20px;
    font-size:28px;
    font-weight:700;
    color:#111827;
}

.notif-container{
    display:flex;
    flex-direction:column;
    gap:18px;
}

.notif-card{
    background:white;
    border-radius:16px;
    padding:20px;
    border:1px solid #e5e7eb;
    box-shadow:0 2px 10px rgba(0,0,0,0.04);
}

.notif-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:12px;
}

.notif-status{
    font-weight:600;
    color:#233B8F;
    margin-right:12px;
}

.notif-date{
    color:#64748b;
    font-size:13px;
}

.notif-body p{
    margin-bottom:14px;
    line-height:1.7;
    color:#111827;
}

.notif-details{
    margin-top:6px;
    color:#374151;
}

/* Bouton supprimer tout */
.btn-delete-all{
    background:#dc2626;
    color:white;
    border:none;
    padding:10px 18px;
    border-radius:10px;
    cursor:pointer;
    margin-bottom:20px;
    font-weight:600;
    transition:0.2s;
}

.btn-delete-all:hover{
    background:#b91c1c;
}

/* Bouton supprimer une notification */
.btn-delete-one{
    background:#ef4444;
    color:white;
    border:none;
    padding:7px 12px;
    border-radius:8px;
    cursor:pointer;
    font-size:12px;
    font-weight:600;
    transition:0.2s;
}

.btn-delete-one:hover{
    background:#dc2626;
}

.empty-msg{
    color:#64748b;
    font-size:16px;
}

</style>

@endsection