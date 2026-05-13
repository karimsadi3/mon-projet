<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Emprunt extends Model
{
    protected $fillable = [
        'user_id',
        'livre_id',
        'date_emprunt',
        'date_retour_prevue',
        'date_retour_effective',
        'statut',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function livre()
    {
        return $this->belongsTo(Livre::class);
    }

    public function penalites()
    {
        return $this->hasMany(Penalite::class);
    }
}