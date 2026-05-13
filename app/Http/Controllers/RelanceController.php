<?php

namespace App\Http\Controllers;

use App\Models\Emprunt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Notifications\RetardNotification;

class RelanceController extends Controller
{
    // Affiche la liste des retards, SANS appeler Ollama
    public function index()
    {
        $emprunts = Emprunt::with(['user', 'livre'])
            ->where('statut', 'en_cours')
            ->whereDate('date_retour_prevue', '<', now())
            ->get();

        $retards = [];

        foreach ($emprunts as $emprunt) {
            $joursRetard = (int) now()->diffInDays($emprunt->date_retour_prevue);
            $penalite    = $joursRetard * (float) env('PENALITE_PAR_JOUR', 0.50);

            $retards[] = [
                'id'           => $emprunt->id,
                'nom'          => $emprunt->user->name ?? 'Inconnu',
                'titre'        => $emprunt->livre->titre ?? 'Inconnu',
                'date_emprunt' => \Carbon\Carbon::parse($emprunt->date_emprunt)->format('d/m/Y'),
                'date_retour'  => \Carbon\Carbon::parse($emprunt->date_retour_prevue)->format('d/m/Y'),
                'jours_retard' => $joursRetard,
                'penalite'     => number_format($penalite, 2),
            ];
        }

        return view('relances.index', compact('retards'));
    }

    // ✅ Génère le message IA SANS envoyer la notification
    public function genererMessage($id)
    {
        set_time_limit(120);

        $emprunt = Emprunt::with(['user', 'livre'])->findOrFail($id);

        $joursRetard    = (int) now()->diffInDays($emprunt->date_retour_prevue);
        $penalite       = $joursRetard * (float) env('PENALITE_PAR_JOUR', 0.50);
        $nom            = $emprunt->user->name ?? 'Inconnu';
        $titre          = $emprunt->livre->titre ?? 'Inconnu';
        $dateEmprunt    = \Carbon\Carbon::parse($emprunt->date_emprunt)->format('d/m/Y');
        $dateRetour     = \Carbon\Carbon::parse($emprunt->date_retour_prevue)->format('d/m/Y');
        $bibliothecaire = auth()->user()->name ?? 'Le bibliothécaire';

        $prompt = "Tu es un bibliothécaire. Écris un message court (3-4 phrases) de relance poli et professionnel en français.

    Nom : $nom
    Livre : $titre
    Date d'emprunt : $dateEmprunt
    Date de retour prévue : $dateRetour
    Jours de retard : $joursRetard
    Pénalité : " . number_format($penalite, 2) . " EUR

    Signe avec : $bibliothecaire, bibliothécaire.";

        $messageIA = "Ollama indisponible.";

        try {
            $response = Http::timeout(90)->post('http://localhost:11434/api/generate', [
                'model'  => 'mistral',
                'prompt' => $prompt,
                'stream' => false,
            ]);

            if ($response->successful()) {
                $messageIA = $response->json()['response'] ?? "Réponse vide.";
            }
        } catch (\Exception $e) {
            $messageIA = "Erreur : " . $e->getMessage();
        }

        // ✅ On retourne juste le message, sans envoyer la notification
        return response()->json([
            'message' => $messageIA
        ]);
    }

    // ✅ Envoie la notification uniquement si l'admin/bibliothécaire clique sur "Envoyer"
    public function envoyer(Request $request, $id)
    {
        $emprunt = Emprunt::with(['user', 'livre'])->findOrFail($id);

        $message     = $request->input('message');
        $titre       = $emprunt->livre->titre ?? 'Inconnu';
        $dateEmprunt = \Carbon\Carbon::parse($emprunt->date_emprunt)->format('d/m/Y');

        // 🔔 Envoi de la notification à l'utilisateur
        $emprunt->user->notify(
            new RetardNotification($message, $titre, $dateEmprunt)
        );

        return response()->json([
            'success' => true,
            'message' => 'Notification envoyée avec succès.'
        ]);
    }
}