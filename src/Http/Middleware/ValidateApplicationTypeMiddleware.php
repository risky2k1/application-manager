<?php

namespace Risky2k1\ApplicationManager\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Risky2k1\ApplicationManager\Models\ApplicationCategory;
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
        $types = ApplicationCategory::pluck('key')->toArray();
        if (!in_array($request->route('type'), $types)) {
            abort(404);
        }
        return $next($request);
    }
}
