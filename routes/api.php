<?php

use App\Http\Controllers\Api\BookSummaryController;

// Résumé IA d'un livre
Route::get('/livres/{id}/summary', [BookSummaryController::class, 'summary']);

// Recommandations IA
Route::get('/livres/{id}/recommandations', [BookSummaryController::class, 'recommandations']);