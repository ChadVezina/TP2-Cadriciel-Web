<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserOwnsResource
{
    /**
     * Handle an incoming request to ensure the user owns the resource.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $param = 'resource'): Response
    {
        $resource = $request->route($param);

        if ($resource && method_exists($resource, 'isOwnedBy')) {
            if (!$resource->isOwnedBy($request->user())) {
                abort(403, __('Vous n\'êtes pas autorisé à accéder à cette ressource.'));
            }
        } elseif ($resource && property_exists($resource, 'user_id')) {
            if ($resource->user_id !== $request->user()->id) {
                abort(403, __('Vous n\'êtes pas autorisé à accéder à cette ressource.'));
            }
        }

        return $next($request);
    }
}
