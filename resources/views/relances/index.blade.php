@extends('layout')

@section('content')
<div class="relances-container">

    <h2 class="relances-title">
        📚 Emprunteurs en retard
    </h2>

    @if(count($retards) === 0)
        <div class="empty-state">
            ✅ Aucun retard en cours.
        </div>
    @else
    <div class="table-wrapper">
        <table class="relances-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Livre</th>
                    <th>Date emprunt</th>
                    <th>Date retour prévue</th>
                    <th>Jours de retard</th>
                    <th>Pénalité</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($retards as $retard)
                <tr class="data-row">
                    <td class="td-nom">{{ $retard['nom'] }}</td>
                    <td class="td-titre">{{ $retard['titre'] }}</td>
                    <td>{{ $retard['date_emprunt'] }}</td>
                    <td>{{ $retard['date_retour'] }}</td>
                    <td class="td-center">
                        <span class="badge-retard">
                            {{ round($retard['jours_retard']) }} j
                        </span>
                    </td>
                    <td class="td-center">
                        <span class="badge-penalite">
                            {{ number_format(abs($retard['penalite']), 2) }} €
                        </span>
                    </td>
                    <td class="td-center">
                        <button class="btn-relancer" data-id="{{ $retard['id'] }}">
                            📩 Relancer
                        </button>
                    </td>
                </tr>

                {{-- LIGNE MESSAGE IA --}}
                <tr id="row-msg-{{ $retard['id'] }}" class="msg-row" style="display:none;">
                    <td colspan="7">
                        <div class="msg-box">
                            <p class="msg-label">💬 Message généré par l'IA</p>
                            <p id="msg-{{ $retard['id'] }}" class="msg-content"></p>

                            {{-- ✅ Bouton Envoyer (caché jusqu'à génération) --}}
                            <div style="margin-top:14px;display:flex;gap:10px;align-items:center;">
                                <button
                                    id="btn-envoyer-{{ $retard['id'] }}"
                                    data-id="{{ $retard['id'] }}"
                                    class="btn-envoyer"
                                    style="display:none;">
                                    ✉️ Envoyer la notification
                                </button>

                                <span
                                    id="envoye-ok-{{ $retard['id'] }}"
                                    style="display:none;color:#16a34a;font-weight:bold;font-size:14px;">
                                    ✅ Notification envoyée !
                                </span>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

// ✅ Générer le message IA
document.querySelectorAll('.btn-relancer').forEach(btn => {
    btn.addEventListener('click', function () {
        const id      = this.dataset.id;
        const msgRow  = document.getElementById('row-msg-' + id);
        const msgText = document.getElementById('msg-' + id);
        const btnEnvoyer = document.getElementById('btn-envoyer-' + id);
        const envoyeOk   = document.getElementById('envoye-ok-' + id);

        // Reset état
        btnEnvoyer.style.display = 'none';
        envoyeOk.style.display   = 'none';
        btnEnvoyer.disabled      = false;
        btnEnvoyer.textContent   = '✉️ Envoyer la notification';
        btnEnvoyer.style.background = '#16a34a';

        msgRow.style.display = 'table-row';
        msgText.textContent  = '⏳ Génération du message en cours...';
        this.disabled        = true;
        this.textContent     = '⏳ En cours...';
        this.classList.add('btn-loading');

        fetch(`/relances/${id}/message`)
            .then(r => r.json())
            .then(data => {
                msgText.textContent = data.message;

                // ✅ Afficher le bouton envoyer avec le message stocké
                btnEnvoyer.style.display   = 'inline-block';
                btnEnvoyer.dataset.message = data.message;

                this.disabled     = false;
                this.textContent  = '🔄 Régénérer';
                this.classList.remove('btn-loading');
                this.classList.add('btn-done');
            })
            .catch(() => {
                msgText.textContent = '❌ Erreur de connexion à Ollama.';
                this.disabled       = false;
                this.textContent    = '📩 Relancer';
                this.classList.remove('btn-loading');
            });
    });
});

// ✅ Envoyer la notification
document.addEventListener('click', function(e) {
    if (!e.target.classList.contains('btn-envoyer')) return;

    const id      = e.target.dataset.id;
    const message = e.target.dataset.message;
    const envoyeOk = document.getElementById('envoye-ok-' + id);

    e.target.disabled    = true;
    e.target.textContent = '⏳ Envoi...';

    fetch(`/relances/${id}/envoyer`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ message })
    })
    .then(r => r.json())
    .then(data => {
        e.target.style.display = 'none';
        envoyeOk.style.display = 'inline-block';
    })
    .catch(() => {
        e.target.textContent    = '❌ Erreur';
        e.target.disabled       = false;
    });
});
</script>

<style>
.relances-container {
    max-width: 900px;
    margin: 40px auto;
    padding: 0 32px;
}

.relances-title {
    font-size: 26px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 10px;
}



.relances-table {
    width: 100%;
    border-collapse: collapse;
    background: #ffffff;
    font-size: 18px;
}

.relances-table thead tr {
    background: #233B8F;
}

.relances-table th {
    color: white;
    font-weight: 600;
    font-size: 13px;
    padding: 16px;
    text-align: left;
}

.data-row {
    border-top: 1px solid #edf2f7;
    transition: 0.2s;
}

.data-row:hover {
    background: #f8fbff;
}

.relances-table td {
    padding: 15px 16px;
    color: #111827;
}

.badge-retard {
    background: #FDE8E8;
    color: #B42318;
    padding: 5px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
}

.badge-penalite {
    background: #FEF3C7;
    color: #B45309;
    padding: 5px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
}

.btn-relancer {
    background: #233B8F;
    color: white;
    border: none;
    padding: 8px 18px;
    border-radius: 10px;
    cursor: pointer;
    font-size: 13px;
    font-weight: 600;
    transition: 0.2s;
}

.btn-relancer:hover {
    background: #1B2F73;
}

.btn-envoyer {
    background: #16a34a;
    color: white;
    border: none;
    padding: 8px 18px;
    border-radius: 10px;
    cursor: pointer;
    font-size: 13px;
    font-weight: 600;
    transition: 0.2s;
}

.btn-envoyer:hover {
    background: #15803d;
}

.msg-box {
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    padding: 16px;
}

.msg-label {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: .05em;
    color: #64748B;
    margin-bottom: 8px;
}

.msg-content {
    color: #1E293B;
    line-height: 1.7;
    font-size: 14px;
}

.empty-state {
    background: #f0fdf4;
    color: #16a34a;
    padding: 20px;
    border-radius: 12px;
    font-weight: 600;
    text-align: center;
}
</style>

@endsection