<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->isDirector()) {
            abort(403, 'Доступ запрещён. Только директор может вносить изменения.');
        }

        return $next($request);
    }
}
