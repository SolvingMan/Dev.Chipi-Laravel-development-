<?php

namespace App\Http\Middleware;

use Closure;

class CheckCartEmpty
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
        if (!isset($_SESSION['products']) || count($_SESSION['products']) == 0) {
            return redirect("/cart");
        }

        return $next($request);
    }
}
