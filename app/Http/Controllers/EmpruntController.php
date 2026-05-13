<?php

namespace App\Http\Controllers;

use App\Models\Emprunt;
use App\Models\Livre;
use App\Models\Penalite;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmpruntController extends Controller
{
    public function emprunter(Livre $livre)
    {
        $userId = auth()->id();

        $penaliteImpayee = Penalite::where('user_id', $userId)
            ->where('payee', false)
            ->exists();

        if ($penaliteImpayee) {
            return back()->with('error', 'Emprunt interdit : vous avez une pénalité impayée.');
        }

        $retardEnCours = Emprunt::where('user_id', $userId)
            ->where('statut', 'en_cours')
            ->whereDate('date_retour_prevue', '<', now())
            ->exists();

        if ($retardEnCours) {
            return back()->with('error', 'Emprunt interdit : vous avez un livre en retard non retourné.');
        }

        if ($livre->stock <= 0) {
            return back()->with('error', 'Livre non disponible.');
        }

        Emprunt::create([
            'user_id' => $userId,
            'livre_id' => $livre->id,
            'date_emprunt' => now(),
            'date_retour_prevue' => now()->subDays(7),
            'statut' => 'en_cours',
            'notifie_retard' => false,
        ]);

        $livre->decrement('stock');

        return back()->with('success', 'Livre emprunté avec succès.');
    }

    public function index()
    {
        $this->verifierRetardsUtilisateurConnecte();
        $user = auth()->user();

        if (in_array($user->role, ['admin', 'bibliothecaire'])) {
            $emprunts = Emprunt::with(['livre', 'user'])->latest()->get();
        } else {
            $emprunts = Emprunt::with('livre')
                ->where('user_id', $user->id)
                ->latest()
                ->get();
        }

        return view('emprunts.index', compact('emprunts'));
    }

    public function createAdmin()
    {
        $users = User::where('role', 'adherent')->get();
        $livres = Livre::where('stock', '>', 0)->get();

        return view('emprunts.create_admin', compact('users', 'livres'));
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'livre_id' => 'required|exists:livres,id',
        ]);

        $userId = $request->user_id;
        $livre = Livre::findOrFail($request->livre_id);

        $penaliteImpayee = Penalite::where('user_id', $userId)
            ->where('payee', false)
            ->exists();

        if ($penaliteImpayee) {
            return back()->with('error', 'Emprunt interdit : cet utilisateur a une pénalité impayée.');
        }

        $retardEnCours = Emprunt::where('user_id', $userId)
            ->where('statut', 'en_cours')
            ->whereDate('date_retour_prevue', '<', now())
            ->exists();

        if ($retardEnCours) {
            return back()->with('error', 'Emprunt interdit : cet utilisateur a un livre en retard non retourné.');
        }

        if ($livre->stock <= 0) {
            return back()->with('error', 'Livre non disponible.');
        }

        Emprunt::create([
            'user_id' => $userId,
            'livre_id' => $livre->id,
            'date_emprunt' => now(),
            'date_retour_prevue' => now()->addDays(7),
            'statut' => 'en_cours',
            'notifie_retard' => false,
        ]);

        $livre->decrement('stock');

        return redirect()->route('emprunts.index')->with('success', 'Emprunt créé avec succès pour l’utilisateur.');
    }

    public function retourner(Emprunt $emprunt)
    {
        if ($emprunt->statut === 'retourne') {
            return back()->with('error', 'Ce livre a déjà été retourné.');
        }

        $emprunt->update([
            'date_retour_effective' => now(),
            'statut' => 'retourne',
        ]);

        $emprunt->livre->increment('stock');

        $dateRetourPrevue = Carbon::parse($emprunt->date_retour_prevue);
        $dateRetourEffective = Carbon::parse($emprunt->date_retour_effective);

        if ($dateRetourEffective->gt($dateRetourPrevue)) {
            $joursRetard = $dateRetourPrevue->diffInDays($dateRetourEffective);
            $montant = $joursRetard * 0.50;

            Penalite::create([
                'user_id' => $emprunt->user_id,
                'emprunt_id' => $emprunt->id,
                'montant' => $montant,
                'payee' => false,
            ]);
        }

        return back()->with('success', 'Livre retourné avec succès.');
    }
    private function verifierRetardsUtilisateurConnecte(): void
{
    $user = auth()->user();

    if (!$user) {
        return;
    }

    $empruntsEnRetard = \App\Models\Emprunt::with('livre')
        ->where('user_id', $user->id)
        ->where('statut', 'en_cours')
        ->whereDate('date_retour_prevue', '<', now())
        ->where(function ($query) {
            $query->whereNull('notifie_retard')
                  ->orWhere('notifie_retard', false);
        })
        ->get();

    foreach ($empruntsEnRetard as $emprunt) {
        $user->notify(new \App\Notifications\OverdueNotification($emprunt));

        $emprunt->update([
            'notifie_retard' => true,
        ]);
    }
}
}