<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next) : Response
    {
        // Verificar si el rol coincide
        $userRole = Auth::user()->role_id;

        if ($userRole != 1) {
            return redirect()->route('dashboard')->with('error', 'No tienes acceso a esta sección.');
        }

        return $next($request);
    }
}
