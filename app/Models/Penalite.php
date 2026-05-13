<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penalite extends Model
{
    protected $fillable = [
        'user_id',
        'emprunt_id',
        'montant',
        'payee',
    ];

    protected $casts = [
        'payee' => 'boolean',
        'montant' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function emprunt()
    {
        return $this->belongsTo(Emprunt::class);
    }
}