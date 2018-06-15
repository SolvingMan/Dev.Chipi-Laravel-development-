<?php

namespace App\Http\Controllers;

use App\Models\Aliexpress\Aliexpress;
use App\Models\Ebay\EbayCategories;
use App\Marketing;
use App\Models\Cart;
use App\Models\Checkout;
use App\User;
use App\Util;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    private $currentTab;
    private $timerTime;

    function __construct()
    {
        parent::__construct();
        $data = Util::chooseCurrentTabAndTimer();
        $this->currentTab = $data["tab"];
        $this->timerTime = $data["timerTime"];
    }

    function search()
    {
        $keyword = Input::get("keyword");
        $result = [];
        $search = \DB::table('search')->where('hebrew', 'like', "%" . $keyword . "%")
            ->select("hebrew")->limit(10)->distinct()->get();

        foreach ($search as $key => $keyword) {
            $result[] = $keyword->hebrew;
        }

        $result = json_encode($result);
        return $result;
    }

    function getStreetForAutocomplete()
    {
        $startWith = Input::get("startWith");
        $city = Input::get("city");
        $result = [];
        $city = \DB::table("cities")->where("cityName", "=", $city)->first();
        if (isset($city->id)) {
            $search = \DB::table('streets')->where('streetName', 'like', "%" . $startWith . "%")
                ->select("streetName")->where("cityID", "=", $city->id)->distinct()->limit(10)->get();
        } else {
            $search = \DB::table('streets')->where('streetName', 'like', "%" . $startWith . "%")
                ->select("streetName")->distinct()->limit(10)->get();
        }
        foreach ($search as $key => $keyword) {
            $result[] = $keyword->streetName;
        }

        $result = json_encode($result);
        // return $search;

        return $result;
    }

    function getCityForAutocomplete()
    {
        $startWith = Input::get("startWith");

        $result = [];
        $search = \DB::table('cities')->where('cityName', 'like', "%" . $startWith . "%")
            ->select("cityName")->limit(10)->distinct()->get();

        foreach ($search as $key => $keyword) {
            $result[] = $keyword->cityName;
        }

        $result = json_encode($result);
        // return $search;

        return $result;
    }

    // make request to payment system to obtain iframe with payments
    // and return to js
    function getIframeData()
    {
//        $discount = Input::get('discount');
        // get data for iframe
        $data = Checkout::paymentResponseData(Cart::getProducts());

        return $data;
    }

    function checkEmail()
    {
        $email = Input::get('email');
        $result = \DB::table('mailingList')->where("email", $email)->get();

        $found = count($result);

        // add to base if there is no email
        if ($found == 0) {
            \DB::table('mailingList')->insert([
                'email' => $email,
                'dateRegister' => date("Y-m-d"),
                'ip' => Checkout::getRealIpAddr(),
                'power' => 1
            ]);
//            Marketing::signInNotify($email, "anonymous");
        }

        return count($result);
    }

    // update user on leave checkout step 1
    function updateUser()
    {
        // get all form data and convert it to array
        $inputs = Input::get('data');
        parse_str($inputs, $data);

        // remove token from data for there are no such field in db
        unset($data['_token']);

        (new User())->update($data, ["id" => $_SESSION['user']->id]);
        $_SESSION['user'] = User::getBy("id", $_SESSION['user']->id);

        return $data;
    }

    function storeNotice()
    {
        $notice = Input::get('notice');

        $userId = $_SESSION['user']->id;
        $_SESSION["user_{$userId}_notice"] = $notice;

        return $_SESSION["user_{$userId}_notice"];
    }

    function storeCouponCode()
    {
        $coupon = Input::get('couponCode');
        $coupon = strtolower($coupon);
        $discount = \DB::table("coupons")->select("sumCoupon")
            ->where("codeCoupon", "=", $coupon)
            ->where("power", "=", '1')
            ->where("dateEndCoupon", ">", date("Y-m-d"))
            ->first();
        if ($discount) {
            $discount = $discount->sumCoupon;
            $data["errorCode"] = 200;
            $data["discount"] = $discount;
        } else {
            $data["errorCode"] = 404;
            $data["discount"] = 0;
        }
        Checkout::storeCoupon($coupon, $_SESSION['user']->id);
        return $data;
    }

    public function MoreOneDayDeals(Request $request)
    {

        $id = $request->id;
        $new_id = $id + 30;

        $ebayData = \DB::table('products')->orderBy('itemId', 'desc')->distinct()->select('itemId', 'title', 'picture', 'price')->skip($id)->take($new_id)->get();

        foreach ($ebayData as $item) {
            $item->price = Util::replacePrices($item->price);
            $item->discount = ceil(mt_rand(30, 50));
            $item->originalPrice = ceil($item->price * ($item->discount / 100 + 1));
        }

        $siteslug = "ebay";
        $productBaseRoute = "/{$siteslug}/product";

        return view("ebay.moreDayDeals", [
            'productBase' => $productBaseRoute,
            'id' => $new_id,
            'ebayData' => $ebayData]);
    }

    public function MoreOneDayDealsAliexpress(Request $request)
    {
        $limit = 12;
        $offset = $request->id + $limit;
        $aliexpressModel = new Aliexpress();
        $widgetId = $request->widgetId;
        $phase = $request->phase;
        $currentSelectedTab = $request->currentSelectedTab;

        $aliData = $aliexpressModel->getAliDeals($limit, $offset, $widgetId, $phase);

        $aliData = $aliexpressModel->parseDealsData($aliData);
        $siteslug = "aliexpress";
        $productBaseRoute = "/{$siteslug}/product";

        return view("aliexpress.moreDayDeals", [
            'productBase' => $productBaseRoute,
            'id' => $offset,
            'aliData' => $aliData->gpsProductDetails,
            'currentTab' => $this->currentTab,
            'currentSelectedTab' => $currentSelectedTab,
        ]);
    }
}