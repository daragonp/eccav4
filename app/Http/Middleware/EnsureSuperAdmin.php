<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'No autorizado.');
        }

        if ($this->isSuperAdmin($user)) {
            return $next($request);
        }

        abort(403, 'Esta acción solo está permitida para superadministradores.');
    }

    private function isSuperAdmin($user): bool
    {
        if ((int) ($user->role_id ?? 0) === 1) {
            return true;
        }

        if (method_exists($user, 'hasRole')) {
            try {
                if (
                    $user->hasRole('super-admin')
                    || $user->hasRole('superadministrador')
                    || $user->hasRole('super admin')
                    || $user->hasRole('Superadministrador')
                ) {
                    return true;
                }
            } catch (\Throwable $e) {
                // Si falla la integración de roles, continuar con no autorizado.
            }
        }

        try {
            if (method_exists($user, 'roles')) {
                $roleNames = $user->roles()->pluck('name')->map(fn ($name) => mb_strtolower((string) $name));
                if (
                    $roleNames->contains('super-admin')
                    || $roleNames->contains('superadministrador')
                    || $roleNames->contains('super admin')
                ) {
                    return true;
                }
            }
        } catch (\Throwable $e) {
            // Si falla la relación, continuar no autorizado.
        }

        return false;
    }
}
