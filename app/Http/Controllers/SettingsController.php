<?php

namespace App\Http\Controllers;

use View;
use Request;
use Config;
use Auth;
use Redirect;
use Mcamara\LaravelLocalization\LaravelLocalization;
use URL;

class SettingsController extends Controller
{
    private $localization;

    public function __construct()
    {
        $this->localization = new LaravelLocalization();
        // во всех конструкторах необходимо кинуть локаль и список поддерживаемых локалей в шаблонизатор, чтобы реализовать переключатель языка. Для примера бросил здесь, но лучше где-нить в Controller::constructor'е
        View::share('locale', \Lang::getLocale());
        View::share('locales', $this->localization->getSupportedLocales());
    }

    public function setLocale()
    {
        $addrLOCALE = Request::segment(1);
        $cfgLOCALE = Config::get('app.locale');
        $segments = Request::segments();
        $locales = $this->localization->getSupportedLocales();
        $locales = array_keys($locales);
        $setLocale = null;

        if (in_array($addrLOCALE, $locales)) {
            // локаль в адресе действительна
            // ставим её
            $setLocale = $addrLOCALE;
        } else {
            // локаль в адресе НЕ действительна
            // ставим дефолтную
            $setLocale = $cfgLOCALE;
        }

        if (Auth::check()) {
            // пользователь авторизован
            // сохраняем в пользователя
            $user = Auth::getUser();
            $user->locale = $setLocale;
            $user->save();
        }

        // ставим локаль в адрес
        if (!empty($segments) && $segments[1] == 'set-language') {
            // ПОПЫТКА (!) уйти туда же откуда и пришли, заменив язык в адресе
            // TODO: не совсем уместно, но на уровне REST'a проблем возникнуть не должно
            $lc = implode('|', $locales);
            $back = URL::previous();
            $back = preg_replace("#/([" . $lc . "].)#iu", "/{$setLocale}", $back);
            return Redirect::secure($back);
        } else {
            // простой переход на главную страницу
            return Redirect::secure('/' . $setLocale);
        }
    }
}