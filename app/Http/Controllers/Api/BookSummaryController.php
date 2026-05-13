<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Livre;
use Illuminate\Support\Facades\Http;

class BookSummaryController extends Controller
{
    public function summary($id)
    {
        $livre = Livre::with(['auteur', 'categorie'])->findOrFail($id);

        $titre = $livre->titre;
        $auteur = $livre->auteur->nom ?? 'Inconnu';
        $categorie = $livre->categorie->nom ?? 'Inconnue';
        $resume = $livre->resume;

        $prompt = "Tu es un bibliothécaire expert. Voici un livre :\nTitre : $titre\nAuteur : $auteur\nCatégorie : $categorie\nRésumé : $resume\n\nFais un résumé clair en 3-4 phrases pour donner envie de le lire. Réponds uniquement en français.";

        $response = Http::timeout(60)->post('http://localhost:11434/api/generate', [
            'model'  => 'mistral',
            'prompt' => $prompt,
            'stream' => false,
        ]);

        $result = $response->json();

        return response()->json([
            'titre'     => $titre,
            'auteur'    => $auteur,
            'resume_ai' => $result['response'] ?? 'Erreur IA',
        ]);
    }

    public function recommandations($id)
    {
        $livre = Livre::with(['auteur', 'categorie'])->findOrFail($id);

        $autresLivres = Livre::with(['auteur', 'categorie'])
            ->where('id', '!=', $id)
            ->where('categorie_id', $livre->categorie_id)
            ->take(5)
            ->get();

        $liste = $autresLivres->map(function($l) {
            $nom = $l->auteur->nom ?? 'Inconnu';
            return "- {$l->titre} de $nom";
        })->join("\n");

        $titre = $livre->titre;
        $cat = $livre->categorie->nom ?? '';

        $prompt = "Tu es un bibliothécaire. Un lecteur a aimé '$titre' (catégorie : $cat).\nVoici d'autres livres disponibles :\n$liste\n\nRecommande 2 ou 3 de ces livres en 1 phrase chacun. Réponds uniquement en français.";

        $response = Http::timeout(60)->post('http://localhost:11434/api/generate', [
            'model'  => 'mistral',
            'prompt' => $prompt,
            'stream' => false,
        ]);

        $result = $response->json();

        return response()->json([
            'livre_base'      => $titre,
            'recommandations' => $result['response'] ?? 'Erreur IA',
        ]);
    }
}