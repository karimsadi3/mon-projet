<?php

namespace App\Http\Controllers;

use App\Models\Penalite;

class PenaliteController extends Controller
{
    public function index()
    {
        $penalites = Penalite::with(['user', 'emprunt'])
         ->orderBy('id', 'desc') 
            ->get();
            
        return view('penalites.index', compact('penalites'));
    }

        public function payer(\App\Models\Penalite $penalite)
    {
        $penalite->update([
            'payee' => true,
        ]);

        return back()->with('success', 'Pénalité payée avec succès.');
    }
}