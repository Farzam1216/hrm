<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Exceptions\UnauthorizedException;

class hasFullPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission, $route = '')
    {
        $user = Auth::user();
        if (app('auth')->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }
        if ($user->hasRole('admin')) {
            return $next($request);
        }
        
        $currentAction = \Route::currentRouteAction();
        $currentAction = explode('@',$currentAction);
        $routes = is_array($route) ? $route : explode('|', $route);
        foreach ($routes as $route){
            if ($currentAction[1] == $route) {
                return $next($request);
            }
        }
        
        $accessLevelID=DB::table('access_levels')->where('name', 'All Employees')->pluck('id')->first();
        $permissions = is_array($permission) ? $permission : explode('|', $permission);

        foreach ($permissions as $permission) {
            $allowedPermission=$user->getAllPermissions()->where('name', $permission)->where('pivot.access_level_id', $accessLevelID);
            if ($allowedPermission->isNotEmpty()) {
                return $next($request);
            }
            
            $allowedPermission=$user->roles[0]->hasPermissionTo($permission);
            if ($allowedPermission == true) {
                return $next($request);
            }
        }

        throw UnauthorizedException::forPermissions($permissions);
    }
}
