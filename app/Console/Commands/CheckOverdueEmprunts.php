<?php

namespace App\Console\Commands;

use App\Models\Emprunt;
use App\Notifications\OverdueNotification;
use Illuminate\Console\Command;

class CheckOverdueEmprunts extends Command
{
    protected $signature = 'emprunts:check-retard';
    protected $description = 'Envoyer une notification aux utilisateurs ayant un emprunt en retard';

    public function handle(): int
    {
        $emprunts = Emprunt::with('user', 'livre')
            ->where('statut', 'en_cours')
            ->whereDate('date_retour_prevue', '<', now()->toDateString())
            ->where(function ($query) {
                $query->whereNull('notifie_retard')
                      ->orWhere('notifie_retard', false);
            })
            ->get();

        foreach ($emprunts as $emprunt) {
            if ($emprunt->user) {
                $emprunt->user->notify(new OverdueNotification($emprunt));

                $emprunt->update([
                    'notifie_retard' => true,
                ]);
            }
        }

        $this->info('Notifications de retard envoyées : ' . $emprunts->count());

        return self::SUCCESS;
    }
}