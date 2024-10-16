<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AutenticarAdmMiddleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
{
    if ($request->user() && $request->user()->adm == '1') {
        if (!$request->user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice')->with('error', 'Você precisa verificar seu endereço de e-mail para acessar esta página.');
        }
        return $next($request);
    }

    if (!$request->user()) {
        return redirect()->route('login')->with('error', 'Você precisa estar logado para acessar esta página.');
    }

    return redirect()->route('access-denied')->with('error', 'Você não tem permissão para acessar esta página.');
}

}
