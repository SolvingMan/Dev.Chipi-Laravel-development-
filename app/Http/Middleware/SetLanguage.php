<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 29.09.2017
 * Time: 17:40
 */

namespace App\Http\Middleware;

use Closure;
use Auth;
use Config;
use Mcamara\LaravelLocalization\LaravelLocalization;
use Redirect;

class SetLanguage
{
    public function handle($request, Closure $next)
    {
        dd("set");
        $localization = new LaravelLocalization();
        $addrLocale = $request->segment(1);
        $locales = $localization->getSupportedLocales();

        $locales = array_keys( $locales );
        $defaultLocale = Config::get('app.locale');
        if ( !Auth::guest() )
        {
            // ставим локаль из настроек пользователя, если она разрешена
            $userLocale = Auth::user()->locale;
            if ( in_array( $userLocale, $locales ) )
            {
                $localization->setLocale($userLocale);
            }
        }
        else
        {
            // ставим локаль из адреса, если она разрешена
            if ( in_array( $addrLocale, $locales ) )
            {
                $localization->setLocale($addrLocale);
            }
            else
            {
                // иначе отправляем на адрес с локалью по умолчанию
                return Redirect::secure( '/'.$defaultLocale );
            }
        }
        return $next($request);
    }
}