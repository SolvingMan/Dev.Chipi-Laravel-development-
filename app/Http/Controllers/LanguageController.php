<?php

namespace App\Http\Controllers;


use Config;

use Illuminate\Support\Facades\Redirect;


class LanguageController extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function switchLang($lang)
    {
        if (array_key_exists($lang, Config::get('languages'))) {
            session()->put('applocale', $lang);
        }
        return Redirect::back()->withInput();
    }
}