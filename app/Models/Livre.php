<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Livre extends Model
{
    protected $fillable = [
        'titre',
        'resume',
        'annee_publication',
        'stock',
        'image',
        'auteur_id',
        'categorie_id',
    ];

    public function auteur()
    {
        return $this->belongsTo(Auteur::class);
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function emprunts()
    {
        return $this->hasMany(Emprunt::class);
    }
}