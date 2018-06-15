<?php

namespace App\Http\Middleware;

use Closure;

class PreventAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (isset($_SESSION["user"])) {
            return redirect()->back();
        }

        return $next($request);
    }
}
