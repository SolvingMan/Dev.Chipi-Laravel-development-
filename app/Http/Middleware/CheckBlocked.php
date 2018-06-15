<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class CheckBlocked
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
        $_SESSION['user'] = User::getBy("id", $_SESSION['user']->id);
        if ($_SESSION["user"]->status == 2) {
            return redirect("/blocked");
        }

        return $next($request);
    }
}
