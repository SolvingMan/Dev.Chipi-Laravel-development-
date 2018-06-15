<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Mail;
use Mcamara\LaravelLocalization\LaravelLocalization;
//$localization = new LaravelLocalization();

//Route::group(
//    [
//        'prefix' => $localization->setLocale(),
//        'middleware' => 'setlanguage'
//    ],
//    function () {
        Route::get('/', "EbayController@index");


        Route::match(['get', 'post'], 'lang/{lang}', [
            'as' => 'lang.switch',
            'uses' => 'LanguageController@switchLang'
        ]);

// User
        Route::get('profile/{id}', "UserController@index")->middleware('check_auth');
        Route::get('profile/{id}/history', "UserController@history")->middleware('check_auth');
        Route::post('user/update/{id}', "UserController@update")->middleware('check_auth');
        Route::get('/blocked', "UserController@blocked");
        Route::get('/product/{id}/{site}/{title}', "UserController@product");
        Route::post('/user/reportitem', "UserController@reportItem");
        Route::get('/conversation', "UserController@conversation");
        Route::get('/user/ticketChat/{id}', "UserController@ticketChat");

        Route::get('/user/reportChat/{id}', "UserController@reportChat");
        Route::post('/user/addMessageToCustomerService', "UserController@addMessageToCustomerService");

        Route::post('/user/addMessageToReportService', "UserController@addMessageToReportService");
        Route::get('/reportConversation', "UserController@reportConversation");

        Route::get("/user/forgot_password_index", "UserController@forgotPasswordIndex");
        Route::post("/user/forgot_password", "UserController@forgotPassword");


// Cart page
        Route::get('cart', "CartController@index");
        Route::get('cart/remove/{id}', "CartController@remove");
        Route::get('cart/edit/{id}', "CartController@edit");
        Route::post('cart/add', "CartController@add");
        Route::get("/cart/shortcart", "CartController@shortcart");

// checkout
        Route::get('checkout', "CheckoutController@index")->middleware('check_auth', 'blocked', 'cart_empty');
//Route::post('checkout', "CheckoutController@checkout");
        Route::get("/checkout/success", "CheckoutController@success");
        Route::get("/checkout/fail", "CheckoutController@fail");
        Route::get("/checkout/process", "CheckoutController@process");

// Aliexpress
        Route::get('/aliexpress/categoryMap/{categoryID}', "AliexpressController@categoryMap");
        Route::get('/aliexpress/categoryList', "AliexpressController@categoryList");
        Route::get("/aliexpress", "AliexpressController@index");
        Route::get("/aliexpress/category/{title}/{id}/{page}", "AliexpressController@category");
        Route::get("/aliexpress/search/{keyword}/{page}", "AliexpressController@search");
        Route::get("/aliexpress/product/{title}/{id}", "AliexpressController@product");
        Route::get("/aliexpress/additionalImages");
        Route::get("/aliexpress/description/{title}/{id}/{language}", "AliexpressController@description");
        Route::get("/aliexpress/oneDayDeals", "AliexpressController@oneDayDeals");
        Route::post("/aliexpress/MoreOneDayDeals", "AjaxController@MoreOneDayDealsAliexpress");

//Next
        Route::get('/next', "NextController@index");
        Route::get('/next/category/{title}/{id}/{page}', "NextController@category");
        Route::get("/next/search/{keyword}/{page}", "NextController@search");
        Route::get('/next/product/{title}/{id}', "NextController@product");
        Route::get('/next/categoryMap/{categoryID}', "NextController@categoryMap");
        Route::get('/next/categoryList', "NextController@categoryList");

//Asos
        Route::get('/asos', "AsosController@index");
        Route::get('/asos/product/{title}/{id}', "AsosController@product");
        Route::get("/asos/search/{keyword}/{page}", "AsosController@search");
        Route::get('/asos/category/{title}/{id}/{page}', "AsosController@category");
        Route::get('/asos/categoryMap/{categoryID}', "AsosController@categoryMap");
        Route::get('/asos/categoryList', "AsosController@categoryList");

// Ebay
        Route::get('/ebay/categoryMap/{categoryID}', "EbayController@categoryMap");
        Route::get('/ebay/categoryList', "EbayController@categoryList");
        Route::get('/ebay', "EbayController@index");
        Route::get('/ebay/category/{title}/{id}/{page}', "EbayController@category");
        Route::get("/ebay/product/{title}/{id}", "EbayController@product");
        Route::get("/ebay/search/{keyword}/{page}", "EbayController@search");
        Route::get("/ebay/oneDayDeals", "EbayController@oneDayDeals");
        Route::post("/ebay/MoreOneDayDeals", "AjaxController@MoreOneDayDeals");
        Route::get("/ebay/description/{title}/{id}/{language}", "EbayController@description");

// Amazon
        Route::get('/amazon/categoryMap/{categoryID}', "AmazonController@categoryMap");
        Route::get('/amazon/categoryList', "AmazonController@categoryList");
        Route::get('/amazon', "AmazonController@index");
        Route::get('/amazon/category/{title}/{id}/{page}', "AmazonController@category");
        Route::get("/amazon/product/{title}/{id}", "AmazonController@product");
        Route::get("/amazon/search/{keyword}/{page}", "AmazonController@search");
        Route::get("/amazon/oneDayDeals", "AmazonController@oneDayDeals");
        Route::post("/amazon/MoreOneDayDeals", "AjaxController@MoreOneDayDeals");
        Route::get("/amazon/description/{title}/{id}/{language}", "AmazonController@description");

// Authism
        Route::get("/auth", "AuthController@index")->middleware('prevent_auth');
        Route::get("/auth/logout", "AuthController@logout");
        Route::post("/auth/register", "AuthController@register");
        Route::post("/auth/login", "AuthController@login");
        Route::post("/auth/loginfb", "AuthController@loginFB");
        Route::get("/auth/redirect", "AuthController@redirect");

// Utility routes
        Route::get('/main', "MainController@index");
        Route::get('/main/rules', "MainController@rules");
        Route::get('/main/whoWeAre', "MainController@whoWeAre");

        Route::get('/main/customer-service', "MainController@customerService");
        Route::get('/main/contactus', "MainController@contactUs")->middleware('contact_auth');

        Route::post('/main/contactuspost', "MainController@contactUsPost");
        Route::get('/main/questionanswer', 'MainController@questionAnswer');
        Route::get('/main/test/revent', function () {

        });
        Route::get('/main/checkProducts', "MainController@checkProducts");
        Route::get('/main/zipDeterminer', "MainController@zipDeterminer");

// services
        Route::get('/newsletter', 'ServiceController@newsletter');

// ajaxeeeez
        Route::get("/ajax/search", "AjaxController@search");
        Route::post("/ajax/updateUser", "AjaxController@updateUser");
        Route::get("/ajax/storeNotice", "AjaxController@storeNotice");
        Route::get("/ajax/storeCouponCode", "AjaxController@storeCouponCode");
        Route::get("/ajax/loadIframe", "AjaxController@getIframeData");
        Route::get("/ajax/checkEmail", "AjaxController@checkEmail");
        Route::get("/ajax/getCityForAutocomplete", "AjaxController@getCityForAutocomplete");
        Route::get("/ajax/getStreetForAutocomplete", "AjaxController@getStreetForAutocomplete");


        Route::get("/checkout_iframe", function () {
            return view("user.checkout_iframe");
        });

        Route::get("/send", "ServiceController@sendNewsletter");
        Route::get("/unsubscribe/{email}", "ServiceController@unsubscribeNewsletter");
        Route::get("/seeemail/{email}/{date}", "ServiceController@seeEmail");
        Route::get("/usercartemail", "ServiceController@userCartEmail");
        Route::get("/welcomeletter", "ServiceController@welcome");
        // SettingsController
        Route::get('set-language', ['as' => 'set-language', 'uses' => 'SettingsController@setLocale']);

//        // AuthController
//        Route::controllers([
//        'auth'      => 'Auth\AuthController',
//        'password'  => 'Auth\PasswordController',
//    ]);
//    }
//);


// root
