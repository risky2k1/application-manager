<?php

namespace Risky2k1\ApplicationManager\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateApplicationTypeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!array_key_exists($request->route('type'), config('application-manager.application.type'))) {
            abort(404);
        }
        return $next($request);
    }
}
