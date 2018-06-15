<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 23.06.2017
 * Time: 11:35
 */

namespace App\Http\Middleware;

use Closure;
use Cookie;
use Response;

class Affiliate
{
    public function handle($request, Closure $next)
    {
        $affiliate = $request->input('affiliate');
        if (isset($affiliate)) {
            SetCookie("affiliate",$affiliate,time()+3600*24);
        }
        return $next($request);
    }
}