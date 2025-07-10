<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();

        if (!in_array($user->role, $roles)) {
            // Redirect berdasarkan role yang tidak diizinkan
            switch ($user->role) {
                case 1:
                    return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
                case 2:
                    return redirect()->route('laporan.index')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
                case 3:
                    return redirect()->route('investor.index')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
                default:
                    return redirect()->route('login')->with('error', 'Peran tidak dikenali.');
            }
        }

        return $next($request);
    }
}
