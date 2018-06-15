<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Input;

class Controller extends BaseController
{
    function __construct()
    {
        $year = 3600 * 24 * 365; // in seconds
        $lifetime = $year;
        $sessionName = "chipy_session";
        session_name($sessionName);
        session_set_cookie_params($lifetime);
        ini_set('memory_limit', '128M');
        ini_set('session.gc_maxlifetime', $lifetime);
//        ini_set('session.cookie_lifetime', $lifetime);
        ini_set('session.use_only_cookies', 1);
        ini_set('session.gc_divisor', 1000);
        ini_set('max_execution_time', 300);
        ini_set('session.name', $sessionName);
        if (isset($_COOKIE['chipy_session'])) {
            session_id($_COOKIE['chipy_session']);
        }
        $affiliate = Input::get('affiliate');
        if (isset($affiliate)) {
            SetCookie("affiliate",$affiliate,time()+3600*24,"/");
        }
        $coupon = Input::get('coupon');
        if (isset($coupon)) {
            SetCookie("coupon",$coupon,time()+3600*24,"/");
        }
//        if (!session_id()) {
            session_start();
//        }

        // get user from cookie if php session expired
        if (!isset($_SESSION['user']) && isset($_COOKIE['usr_id'])) {
            $_SESSION['user'] = \DB::table('users')->where('id', "=", $_COOKIE['usr_id'])->get()->first();
        }
        $_SESSION['products'] = Cart::getProducts();

//        dd($_SERVER);
//        $protocol = 'http';
//        if (isset($_SERVER['HTTPS']))
//            if (strtoupper($_SERVER['HTTPS']) == 'ON')
//                $protocol = 'https';
//
//        header("location: $protocol://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    }

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
