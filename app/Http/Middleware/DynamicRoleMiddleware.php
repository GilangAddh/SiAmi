<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Navigation;
use Illuminate\Support\Facades\Auth;

class DynamicRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $routeName = $request->route()->getName();

        $menu = Navigation::where('url', '/' . $routeName)->first();

        if (!$menu) {
            abort(404);
        }

        $roles = $menu->roles;

        if (Auth::check()) {
            if (in_array(Auth::user()->role, $roles)) {
                return $next($request);
            } else {
                return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
            }
        }

        return redirect()->route('login');
    }
}
