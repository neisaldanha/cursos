<?php
namespace App\Http\Middleware;

use Closure;

class RedirectHttps
{
    public function handle($request, Closure $next)
    {
        if (!$request->secure() && env('APP_ENV') === 'dev')
        {
            return redirect()->secure($request->getRequestUri());
        }
        return $next($request);
    }
}
