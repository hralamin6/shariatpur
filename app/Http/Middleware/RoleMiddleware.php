<?php

namespace App\Http\Middleware;

use App\Models\Permission;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        if ($user){
            $permissions = Permission::all();
            foreach ($permissions as $permission){
                \Illuminate\Support\Facades\Gate::define($permission->slug, function (User $user) use ($permission){
                    return $user->hasPermission($permission->slug);
                });
            }
        }
        return $next($request);
    }
}
