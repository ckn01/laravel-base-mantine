<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

// THIS DISABLED
class FilamentAuthorizationMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check specific routes and apply authorization
        $route = $request->route();
        $routeName = $route ? $route->getName() : '';
        
        // Activity Log authorization
        if (str_contains($routeName, 'activitylog')) {
            if (!Gate::allows('view Activity Log')) {
                abort(403, 'Unauthorized to access Activity Log.');
            }
        }
        
        // Jobs Monitor authorization
        if (str_contains($routeName, 'jobs-monitor') || str_contains($routeName, 'monitor-jobs')) {
            if (!Gate::allows('access Queue Monitor')) {
                abort(403, 'Unauthorized to access queue monitor.');
            }
        }
        
        // Health Check authorization
        if (str_contains($routeName, 'health')) {
            if (!Gate::allows('view Health')) {
                abort(403, 'Unauthorized to view health status.');
            }
        }
        
        // Backup authorization
        // if (str_contains($routeName, 'backup')) {
        //     if (!Gate::allows('manage Backup')) {
        //         abort(403, 'Unauthorized to manage backups.');
        //     }
        // }
        
        return $next($request);
    }
}