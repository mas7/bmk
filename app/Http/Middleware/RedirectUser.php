<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

class RedirectUser
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->hasExactRoles(Role::firstWhere('name', 'contractor'))) {
            if (!$request->is('contractor*')) {
                return redirect('/contractor');
            }
        }

        if ($request->user()?->hasExactRoles(Role::firstWhere('name', 'client'))) {
            if (!$request->is('client*')) {
                return redirect('/client');
            }
        }

        if ($request->user()?->hasExactRoles(Role::firstWhere('name', 'super_admin'))) {
            if (!$request->is('admin*')) {
                return redirect('/admin');
            }
        }

        return $next($request);
    }
}
