<?php

namespace App\Http\Controllers;

use App\Models\Livre;
use App\Models\Auteur;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LivreController extends Controller
{
    public function index(Request $request)
    {
        $query = Livre::with(['auteur', 'categorie']);

        if ($request->filled('search')) {
            $query->where('titre', 'like', '%' . $request->search . '%');
        }

        $livres = $query->latest()->paginate(10)->withQueryString();

        return view('livres.index', compact('livres'));
    }

    public function create()
    {
        $auteurs = Auteur::all();
        $categories = Categorie::all();

        return view('livres.create', compact('auteurs', 'categories'));
    }

    public function store(Request $request)
            {
                $request->validate([
                    'titre' => 'required|string|max:255',
                    'resume' => 'nullable|string',
                    'annee_publication' => 'required|integer',
                    'stock' => 'required|integer|min:0',
                    'categorie_id' => 'required|exists:categories,id',
                    'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                ]);

                // ✅ Gestion nouvel auteur
                if ($request->auteur_id === 'nouveau') {

                    $request->validate([
                        'nouveau_auteur_nom' => 'required|string|max:255',
                        'nouveau_auteur_prenom' => 'required|string|max:255',
                    ]);

                    $auteur = Auteur::create([
                        'nom' => $request->nouveau_auteur_nom,
                        'prenom' => $request->nouveau_auteur_prenom,
                    ]);

                    $auteur_id = $auteur->id;

                } else {

                    $request->validate([
                        'auteur_id' => 'required|exists:auteurs,id',
                    ]);

                    $auteur_id = $request->auteur_id;
                }

                $data = $request->all();

                // ✅ Remplace "nouveau" par le vrai ID
                $data['auteur_id'] = $auteur_id;

                if ($request->hasFile('image')) {
                    $data['image'] = $request->file('image')->store('livres', 'public');
                }

                Livre::create($data);

                return redirect()->route('livres.index')->with('success', 'Livre ajouté avec succès.');
            }
    public function edit(Livre $livre)
    {
        $auteurs = Auteur::all();
        $categories = Categorie::all();

        return view('livres.edit', compact('livre', 'auteurs', 'categories'));
    }

    public function update(Request $request, Livre $livre)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'resume' => 'nullable|string',
            'annee_publication' => 'required|integer',
            'stock' => 'required|integer|min:0',
            'auteur_id' => 'required|exists:auteurs,id',
            'categorie_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('livres', 'public');
        }

        $livre->update($data);

        return redirect()->route('livres.index')->with('success', 'Livre modifié avec succès.');
    }

    public function destroy(Livre $livre)
    {
        $livre->delete();

        return redirect()->route('livres.index')->with('success', 'Livre supprimé avec succès.');
    }

    /**
     * PAGE ACCUEIL
     */
    public function home(Request $request)
    {
        $query = Livre::with(['auteur', 'categorie']);

        if ($request->filled('search')) {
            $query->where('titre', 'like', '%' . $request->search . '%');
        }

        $livres = $query->latest()->paginate(10)->withQueryString();

        return view('accueil.home', compact('livres'));
    }

    /**
     * 🔥 RECHERCHE IA (OLLAMA)
     */
    public function rechercheIA(Request $request)
    {
        // ✅ FIX 1 : on donne 3 minutes à PHP pour exécuter cette méthode
        set_time_limit(180);

        $request->validate([
            'prompt' => 'required|string'
        ]);

        $promptUser = $request->prompt;

        $livres = Livre::select('titre', 'resume')
            ->limit(10)
            ->get();

        $catalogue = "";

        foreach ($livres as $livre) {
            $resumeCourt = substr(strip_tags($livre->resume), 0, 40);
            $catalogue .= "
Titre: {$livre->titre}
Résumé: {$resumeCourt}

";
        }

        $prompt = "
Tu es un bibliothécaire professionnel. Voici le catalogue COMPLET de la bibliothèque :

$catalogue

Un utilisateur recherche : \"$promptUser\"

RÈGLES STRICTES :
- Recommande UNIQUEMENT des livres qui existent dans le catalogue ci-dessus
- N'invente AUCUN livre qui ne figure pas dans le catalogue
- Si aucun livre ne correspond, dis-le poliment
- Réponds directement en français sans répéter ces instructions
- Commence par saluer l'utilisateur puis présente tes recommandations
- Pour chaque livre recommandé, explique brièvement pourquoi il correspond
";

        // ✅ FIX 2 : timeout porté à 150s (sous la limite PHP de 180s)
        try {
            $response = Http::timeout(150)->post('http://127.0.0.1:11434/api/generate', [
                'model'  => 'mistral',
                'prompt' => $prompt,
                'stream' => false,
            ]);

            $resultat = $response->json()['response'] ?? 'Aucune réponse IA.';

        // ✅ FIX 3 : on attrape le timeout au lieu de planter avec une 500
        } catch (\Exception $e) {
            $route = auth()->check() ? 'livres.index' : 'home';
            return redirect()
                ->route($route)
                ->with('resultatIA', '⚠️ Ollama n\'a pas répondu à temps. Relancez la recherche.')
                ->with('livresRecommandes', collect());
        }

        // ✅ Extraction intelligente : on cherche les titres exacts de la BDD dans la réponse IA
        $tousLesLivres = Livre::select('id', 'titre')->get();
        $idsRecommandes = [];

        foreach ($tousLesLivres as $l) {
            if (stripos($resultat, $l->titre) !== false) {
                $idsRecommandes[] = $l->id;
            }
        }

        $livresRecommandes = Livre::whereIn('id', $idsRecommandes)
            ->with(['auteur', 'categorie'])
            ->take(5)
            ->get();

        // ✅ Redirect vers home pour visiteur, livres pour connecté
        $route = auth()->check() ? 'livres.index' : 'home';

        return redirect()
            ->route($route)
            ->with('resultatIA', $resultat)
            ->with('livresRecommandes', $livresRecommandes);
    }
}