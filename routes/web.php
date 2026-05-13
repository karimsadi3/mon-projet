<?php

use App\Http\Controllers\LivreController;
use App\Http\Controllers\EmpruntController;
use App\Http\Controllers\PenaliteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UtilisateurController;
use App\Http\Controllers\RelanceController;
use Illuminate\Support\Facades\Route;



// 👤 Utilisateur connecté
Route::middleware(['auth'])->group(function () {

    // Livres
    Route::get('/livres', [LivreController::class, 'index'])->name('livres.index');

    // Emprunt personnel
    Route::post('/emprunter/{livre}', [EmpruntController::class, 'emprunter'])->name('emprunter');

    // Voir ses emprunts
    Route::get('/emprunts', [EmpruntController::class, 'index'])->name('emprunts.index');

    // Retourner un livre
    Route::post('/retourner/{emprunt}', [EmpruntController::class, 'retourner'])->name('retourner');

    // Notifications personnelles
    Route::get('/notifications', function () {

        $notifications = auth()->user()->notifications()->latest()->get();

        return view('notifications.index', compact('notifications'));

    })->name('notifications.index');

    // Supprimer UNE notification
    Route::delete('/notifications/{id}', function ($id) {

        auth()->user()
            ->notifications()
            ->where('id', $id)
            ->delete();

        return back();

    })->name('notifications.delete');

    // Supprimer TOUTES les notifications
    Route::delete('/notifications', function () {

        auth()->user()
            ->notifications()
            ->delete();

        return back();

    })->name('notifications.deleteAll');
});



// 👨‍🏫 Bibliothécaire + Admin
Route::middleware(['auth', 'isBibliothecaire'])->group(function () {

    // Voir les pénalités
    Route::get('/penalites', [PenaliteController::class, 'index'])->name('penalites.index');

    // Créer un emprunt pour un utilisateur
    Route::get('/admin/emprunts/create', [EmpruntController::class, 'createAdmin'])->name('admin.emprunts.create');

    Route::post('/admin/emprunts/store', [EmpruntController::class, 'storeAdmin'])->name('admin.emprunts.store');

    // Gestion utilisateurs
    Route::get('/utilisateurs', [UtilisateurController::class, 'index'])->name('utilisateurs.index');

    Route::get('/utilisateurs/create', [UtilisateurController::class, 'create'])->name('utilisateurs.create');

    Route::post('/utilisateurs', [UtilisateurController::class, 'store'])->name('utilisateurs.store');

    Route::delete('/utilisateurs/{user}', [UtilisateurController::class, 'destroy'])->name('utilisateurs.destroy');
});



// 👨‍💼 Admin seulement
Route::middleware(['auth', 'isAdmin'])->group(function () {

    // Relances
    Route::get('/relances', [RelanceController::class, 'index'])->name('relances.index');

    Route::get('/relances/{id}/message', [RelanceController::class, 'genererMessage'])->name('relances.message');

    // Dashboard
    Route::get('/dashboard', function () {

        $nombreLivres       = \App\Models\Livre::count();
        $nombreUtilisateurs = \App\Models\User::count();
        $livresEmpruntes    = \App\Models\Emprunt::count();
        $livresARetourner   = \App\Models\Emprunt::where('statut', 'en_cours')->count();

        $retards = \App\Models\Emprunt::where('statut', 'en_cours')
            ->whereDate('date_retour_prevue', '<', now())
            ->count();

        $totalPenalites = \App\Models\Penalite::sum('montant');

        $penalitesParUser = \App\Models\Penalite::selectRaw('user_id, SUM(montant) as total')
            ->where('payee', false)
            ->groupBy('user_id')
            ->with('user')
            ->get();

        return view('dashboard', compact(
            'nombreLivres',
            'nombreUtilisateurs',
            'livresEmpruntes',
            'livresARetourner',
            'retards',
            'totalPenalites',
            'penalitesParUser'
        ));

    })->name('dashboard');

    // CRUD livres admin
    Route::get('/livres/create', [LivreController::class, 'create'])->name('livres.create');

    Route::post('/livres', [LivreController::class, 'store'])->name('livres.store');

    Route::get('/livres/{livre}/edit', [LivreController::class, 'edit'])->name('livres.edit');

    Route::put('/livres/{livre}', [LivreController::class, 'update'])->name('livres.update');

    Route::delete('/livres/{livre}', [LivreController::class, 'destroy'])->name('livres.destroy');

    // Paiement pénalité
    Route::post('/penalites/{penalite}/payer', [PenaliteController::class, 'payer'])->name('penalites.payer');
});



// ⚙️ Profil Breeze
Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



// page home
Route::get('/', [LivreController::class, 'home'])->name('home');



// recherche IA visiteur
Route::post('/recherche-ia', [LivreController::class, 'rechercheIA'])
    ->name('recherche.ia');



// recherche IA utilisateurs connectés
Route::post('/livres/recherche-ia', [LivreController::class, 'rechercheIA'])
    ->name('livres.recherche.ia');

    // envoie relance
Route::post('/relances/{id}/envoyer', [RelanceController::class, 'envoyer'])
    ->name('relances.envoyer');


require __DIR__.'/auth.php';