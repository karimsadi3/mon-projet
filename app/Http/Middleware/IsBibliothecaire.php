<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsBibliothecaire
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && in_array($request->user()->role, ['admin', 'bibliothecaire'])) {
            return $next($request);
        }

        return redirect('/livres')->with('error', 'Accès interdit');
    }
}