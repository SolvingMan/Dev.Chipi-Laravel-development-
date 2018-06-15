<?php

namespace App\Http\Middleware;

use Closure;

class ContactAuth
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
        if (!isset($_SESSION['user'])) {
            $_SESSION['from_contactus'] = true;

            return redirect('/auth');
        }
        return $next($request);
    }
}
