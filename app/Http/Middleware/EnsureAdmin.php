<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureAdmin
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

        if ($this->isAdmin($user)) {
            return $next($request);
        }

        abort(403, 'No tiene permisos para acceder a esta sección.');
    }

    private function isAdmin($user): bool
    {
        if (in_array((int) ($user->role_id ?? 0), [1, 2], true)) {
            return true;
        }

        if (method_exists($user, 'hasRole')) {
            try {
                if (
                    $user->hasRole('admin')
                    || $user->hasRole('Admin')
                    || $user->hasRole('administrador')
                    || $user->hasRole('Administrador')
                    || $user->hasRole('super-admin')
                    || $user->hasRole('Superadministrador')
                    || $user->hasRole('superadministrador')
                ) {
                    return true;
                }
            } catch (\Throwable $e) {
                // Ignorar errores de integración de roles y seguir con validaciones locales.
            }
        }

        try {
            if (method_exists($user, 'roles')) {
                $roleNames = $user->roles()->pluck('name')->map(fn ($name) => mb_strtolower((string) $name));
                if (
                    $roleNames->contains('admin')
                    || $roleNames->contains('administrador')
                    || $roleNames->contains('super-admin')
                    || $roleNames->contains('superadministrador')
                ) {
                    return true;
                }
            }

            if (isset($user->role) && (int) ($user->role->id ?? 0) === 1) {
                return true;
            }

            if (method_exists($user, 'role') && in_array((int) optional($user->role()->first())->id, [1, 2], true)) {
                return true;
            }
        } catch (\Throwable $e) {
            // Si la relación falla, se mantiene no admin.
        }

        return false;
    }
}
