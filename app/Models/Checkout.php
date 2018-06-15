<?php

namespace App\Models;

use App\Http\API\AliexpressAPI;
use App\Models\Ebay\EbayCategories;
use App\Product;
use App\Util;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use DB;
use Log;
use Mockery\Exception;

class Checkout extends Model
{
    public static function refreshProductsPrice($products)
    {
        foreach ($products as $key => &$product) {
            try {
                // fetch actual data for each product
                switch ($product->sitename) {
                    case "aliexpress":
                        $product = self::refreshAliexpressProduct($product);
                        break;
                    case "ebay":
                        $product = self::refreshEbayProduct($product);
                        if (!$product) {
                            unset($products[$key]);
                        }
                        break;
                }
            } catch (Exception $ex) {
                unset($products[$key]);
            }


            if (isset($product)) {
                // change quantities when there is not enough products in stock
                if ($product->availableQuantity < $product->quantity) {
                    $product->quantity = $product->availableQuantity;
                }
            }
        }

        // change products in cart
        $_SESSION['products'] = $products;
        Cart::refreshInDB($_SESSION['products']);

        return $products;
    }


//    public static function applyDiscountForProducts($targetCoupon,$products)
//    {
//        $coupon = Checkout::checkCouponCode($targetCoupon);
//        if($coupon !== "") {
//            for ($i = 0; $i < count($products); $i++) {
//                $products[$i]->productPrice = $products[$i]->productPrice - ($products[$i]->productPrice * ($coupon->sumCoupon / 100));
//                $products[$i]->shippingPrice = $products[$i]->shippingPrice - ($products[$i]->shippingPrice * ($coupon->sumCoupon / 100));
//            }
//
//        }
//        return $products;
//    }
    public static function getDiscountPrices($targetCoupon, $products)
    {
        $newProducts = [];
        $coupon = Checkout::checkCouponCode($targetCoupon);
        if ($coupon !== "") {
            for ($i = 0; $i < count($products); $i++) {
                $newProduct = new \stdClass();
                $newProduct->quantity = $products[$i]->quantity;
                $newProduct->availableQuantity = $products[$i]->availableQuantity;
                $newProduct->shippingPrice = $products[$i]->shippingPrice - ($products[$i]->shippingPrice * ($coupon->sumCoupon / 100));
                $newProduct->productPrice = $products[$i]->productPrice - ($products[$i]->productPrice * ($coupon->sumCoupon / 100));
                $newProducts[$i] = $newProduct;
            }

        } else {
            $newProducts = Cart::getProducts();
        }
        return $newProducts;
    }

    /**
     * @param Product $product
     * @return mixed
     */
    public static function refreshAliexpressProduct($product)
    {
        // build a url of product
        $starter = App::getLocale() == "en" ? "www" : App::getLocale();
        $productURLBase = "https://{$starter}.aliexpress.com/item/";
        $url = explode("/", $product->productUrl);

        $productTitle = $url[5];
//        dd($url);
        $productId = explode("?", $url[6])[0];

        // fetch product data again
        $api = new AliexpressAPI($productURLBase . $productTitle . "/" . $productId . ".html");

        if (is_string($product->selectedSku) && $product->selectedSku[0] == '"') {
            $numVariation = json_decode(Cart::escapeJsonString(trim($product->selectedSku, '"')))->numberVariation;
        } else {
            $numVariation = json_decode($product->selectedSku)->numberVariation;
        }

        // all skus of this product
        $sku = $api->getArraySkuProducts();

        $ourSku = new \stdClass();
        // search for one and only
        foreach ($sku as $skusu) {
            if ($skusu->skuPropIds == $numVariation) {
                $ourSku = $skusu;
            }
        }

        // update values
        $product->availableQuantity = $ourSku->skuVal->availQuantity;
        $product->originalProductPrice = isset($ourSku->skuVal->actSkuCalPrice)
            ? $ourSku->skuVal->actSkuCalPrice
            : $ourSku->skuVal->skuCalPrice;
        $product->productPrice = Util::replacePrices($product->originalProductPrice);

        return $product;
    }

    public static function refreshEbayProduct($product)
    {
        $ebayCategories = new EbayCategories();

        $newProduct = $ebayCategories->getProduct($product->productID);

        if (!isset($newProduct->shippingServiceCost) || $newProduct->shippingServiceCost === "-") {
            return null;
        }
        if (isset($product->selectedSku) && $product->selectedSku != "null" && $product->selectedSku != "\"null\"") {
            $selectedSKU = json_decode($product->selectedSku);

            $product->availableQuantity = $newProduct->Variations->Variation[$selectedSKU->numberVariation]->Quantity;
            $product->originalProductPrice = $newProduct->Variations->Variation[$selectedSKU->numberVariation]->StartPrice->OriginalValue;
            $product->productPrice = $newProduct->Variations->Variation[$selectedSKU->numberVariation]->StartPrice->Value;
        } else {
            $product->availableQuantity = $newProduct->Quantity;
            $product->originalProductPrice = $newProduct->ConvertedCurrentPrice->Value;
            $product->productPrice = $newProduct->shekelPrice;
        }

        $product->originalShippingPrice = $newProduct->shippingServiceCost;
        $product->shippingPrice = $newProduct->shekelShippingServiceCost;

//        dd($product);
        return $product;
    }

    public static function getRealIpAddr()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
        {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public static function isMobile()
    {
        $useragent = $_SERVER['HTTP_USER_AGENT'];

        return preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4));
    }

    public static function verifyCheckoutData($data)
    {
        $wrongFields = [];

        $requiredFields = ["name", "email", "city", "street", "building_number", "postal_code"];
        foreach ($requiredFields as $required) {
            if (!isset($data[$required])) {
                $wrongFields[] = $required;
            }
        }

        return $wrongFields;
    }

    static public function processPaymentInterface($vars)
    {
        $urlencoded = http_build_query($vars);

        $CR = curl_init('https://secure.cardcom.co.il/Interface/BillGoldGetLowProfileIndicator.aspx');

        curl_setopt($CR, CURLOPT_POST, 1);
        curl_setopt($CR, CURLOPT_FAILONERROR, true);
        curl_setopt($CR, CURLOPT_POSTFIELDS, $urlencoded);
        curl_setopt($CR, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($CR, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($CR, CURLOPT_FAILONERROR, true);

        $result = curl_exec($CR);
        $error = curl_error($CR);

        if (!empty($error)) Log::error($error);

        $output = array();
        parse_str($result, $output);

        return $output;
    }

    private static function postVars($vars, $PostVarsURL)
    {
        $urlencoded = http_build_query($vars);

        $CR = curl_init($PostVarsURL);
        curl_setopt($CR, CURLOPT_POST, 1);
        curl_setopt($CR, CURLOPT_FAILONERROR, true);
        curl_setopt($CR, CURLOPT_POSTFIELDS, $urlencoded);
        curl_setopt($CR, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($CR, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($CR, CURLOPT_FAILONERROR, true);

        #actual curl execution perfom
        $r = curl_exec($CR);
        $error = curl_error($CR);
        $res = array();
        parse_str($r, $res);
        Log::info("Result : " . json_encode($res));
        Log::info("Error : " . json_encode($error));

        if (!empty($error)) {
            return $error;
        }

        return $r;
    }

    static public function paymentResponseData($products)
    {
        if (env('APP_ENV') == \Config::get('enums.env.LIVE')) {
            $TerminalNumber = 39555; # Company terminal
            $UserName = 'b273baMOpSNgw7iqeFnM';   # API User
        } else {
            $TerminalNumber = 1000; # Company terminal
            $UserName = 'barak9611';   # API User
        }
        $Operation = 1;  # = 1 - Bill Only , 2- Bill And Create Token , 3 - Token Only , 4 - Suspended Deal ( Order).

        #Create Post Information
        // Account vars
        $vars = [];
        $vars['TerminalNumber'] = $TerminalNumber;
        $vars['UserName'] = $UserName;
        $vars["APILevel"] = "10"; // req
        $vars['codepage'] = '65001'; // unicode
        $vars["Operation"] = $Operation;
        $vars['ShowInvoiceHead'] = true;

        $billSum = 0;//set 0
        $targetCoupon = isset($_COOKIE['coupon']) ? $_COOKIE['coupon'] : null;

        if ($targetCoupon) {
            $products = Checkout::getDiscountPrices($targetCoupon, $products);
        } else {
            $products = Cart::getProducts();
        }
        foreach ($products as $product) {
            $quantity = $product->availableQuantity >= $product->quantity ? $product->quantity :
                $product->availableQuantity;
            $billSum += ($product->productPrice + $product->shippingPrice) * $quantity;
        }
        //$billSum = $billSum - ($billSum * $discount);
        //test case
        if (env('APP_ENV') != \Config::get('enums.env.LIVE')) $billSum = 0.01;

        $vars["Language"] = 'he';   // page languge he- hebrew , en - english , ru , ar
        $vars["CoinID"] = '1'; // billing coin , 1- NIS , 2- USD other , article :  https://kb.cardcom.co.il/article/AA-00247/0
        $vars["SumToBill"] = $billSum;// Sum To Bill
        $vars['ProductName'] = \Lang::get('general.total_price'); // Product Name , will how if no invoice will be created.

        // different paths on dev and live
        if (env('APP_ENV') == \Config::get('enums.env.DEV')) {
            $prefix = "http://www.dev.chipi.co.il";
        } else {
            $prefix = "https://www.chipi.co.il/public";
        }

        $vars['IndicatorUrl'] = $prefix . "/checkout/process"; // Product Name , will how if no invoice will be created.
        $vars['SuccessRedirectUrl'] = $prefix . "/checkout/success?usr_id=" . $_SESSION['user']->id; // Success Page
        $vars['ErrorRedirectUrl'] = $prefix . "/checkout/fail?usr_id=" . $_SESSION['user']->id; // Error page


        // data that will be available in process page
        $affiliate = isset($_COOKIE['affiliate']) ? $_COOKIE['affiliate'] : 0;
        $coupon = isset($_COOKIE['coupon']) ? $_COOKIE['coupon'] : null;
        $userId = $_SESSION['user']->id;
        $notice = isset($_SESSION["user_{$userId}_notice"]) ? $_SESSION["user_{$userId}_notice"] : " ";
        $vars['ReturnValue'] = $userId
            . "||" . $billSum
            . "||" . self::getRealIpAddr()
            . "||" . (self::isMobile() ? 2 : 0)
            . "||" . $notice
            . "||" . $affiliate
            . "||" . $coupon;

        $vars["MaxNumOfPayments"] = "1"; // max num of payments to show  to the user

        // Send Data To Bill Gold Server
        $r = Checkout::postVars($vars, 'https://secure.cardcom.co.il/Interface/LowProfile.aspx');
        parse_str($r, $responseArray);

        $data = [];
        $data['ResponseCode'] = $responseArray['ResponseCode'];
        $data['ResponseURL'] = $responseArray['url'];
        $data['Result'] = $r;

        setcookie("payment", 1, time() + 60 * 60 * 24 * 60);

        return $data;
    }

    static public function addSummary($userId, $orderTotalCost, $totalPaid, $creditNumber, $confirmCode, $orderComment,
                                      $ip, $couponCode, $device, $affiliate)
    {
        // add summary to db
//        $summaryId = DB::table('orderSummary')->insertGetId([
//            'summaryUserId' => $userId,
//            'summaryDate' => date("Y-m-d"),
//            'summaryTotalPriceOrder' => $orderTotalCost,
//            'summaryUserPaid' => $totalPaid,
//            'summaryFourNumOfCreditCardPaid' => $creditNumber,
//            'summaryAcceptNumberFromCreditCardCompany' => $confirmCode,
//            'summaryComments' => $orderComment,
//            'summaryEsitmatedDate' => date('Y-m-d', strtotime("+60 days")),
//            'orderIp' => $ip,
//            'couponCode' => $couponCode,
//            'device' => $device,
//            'affiliateNumber' => $affiliate,
//        ]);
        DB::insert('insert into orderSummary (summaryUserId,summaryDate,summaryTotalPriceOrder,
summaryUserPaid,summaryFourNumOfCreditCardPaid,summaryAcceptNumberFromCreditCardCompany,summaryComments,
summaryEsitmatedDate,orderIp,couponCode,device,affiliateNumber) values(?,?,?,?,?,?,?,?,?,?,?,?)', [
            $userId, date("Y-m-d"), $orderTotalCost, $totalPaid, $creditNumber, $confirmCode, $orderComment,
            date('Y-m-d', strtotime("+60 days")), $ip, $couponCode, $device, $affiliate
        ]);
        $summary = DB::table("orderSummary")->orderBy("summaryId", "desc")->first();
        // return newly created summary Id
        return $summary->summaryId;
    }

    static public function addOrderDetail($userId, $originId, $summaryId, $categoryId, $product, $productUrl)
    {
        $quantity = $product->availableQuantity >= $product->quantity ? $product->quantity :
            $product->availableQuantity;
        DB::insert('insert into orderDeatils (orderUserId,orderProductId,urlForNext,orderSummaryId,category,orderProductPic,
orderProductTitle,orderProductOptions,orderProductQuantity,orderProductPrice,orderShippingCost,productPriceWithOutConvert,
productPriceShippingWithOutConvert,site) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?)', [
            $userId, $originId, $productUrl, $summaryId, $categoryId, $product->image, $product->title,
            json_encode($product->selectedSku), $quantity, $product->productPrice, $product->shippingPrice,
            $product->originalProductPrice, $product->originalShippingPrice, $product->sitename,
        ]);
    }

    static public function updateCart($userId, $summaryId)
    {
//        DB::table('cartProduct')
//            ->where('userId', "=", $userId)
//            ->where("status", "=", \Config::get('enums.cart_status.CART'))
//            ->update([
//                'status' => \Config::get('enums.cart_status.ORDERED'),
//                'summaryId' => $summaryId
//            ]);
        DB::update('update cartProduct set status=?,summaryId=? where userId=? and status=?', [
            \Config::get('enums.cart_status.ORDERED'), $summaryId, $userId, \Config::get('enums.cart_status.CART')
        ]);
    }

    static public function addShippingDetails($userId, $summaryId, $notice, $user)
    {
        DB::insert('insert into orderCustomerDetail (userId,cust_fname,orderSummaryId,cust_lname,cust_phone,cust_street,
cust_num_appart,cust_num_enter,cust_num_home,cust_city,cust_zip_code,cust_notice) values(?,?,?,?,?,?,?,?,?,?,?,?)', [
            $userId, $user->name, $summaryId, $user->surname, $user->telephone, $user->street, $user->apartment_number,
            $user->num_enter, $user->building_number, $user->city, $user->postal_code, $notice,
        ]);
    }

    static public function getCoupons()
    {
        return DB::table('coupons')
            ->where('power', "=", 1)
            ->where('dateCoupon', "<=", date('Y-m-d'))
            ->where('dateEndCoupon', ">=", date('Y-m-d'))
            ->get();
    }

    static public function updateSummary($userId, $couponCode)
    {
        $summary = self::getLastSummary($userId);

//        DB::table('orderSummary')
//            ->where('summaryId', '=', $summary->summaryId)
//            ->update([
//                'couponCode' => $couponCode
//            ]);
        DB::update('update orderSummary set couponCode=? where summaryId=?', [$couponCode, $summary->summaryId]);
    }

    static public function getLastSummary($userId)
    {
        return DB::table('orderSummary')
            ->orderBy('summaryId', 'desc')
            ->where('summaryUserId', "=", $userId)
            ->first();
    }

    static public function storeCoupon($couponCode, $userId)
    {
//        DB::table('couponEvents')->insert([
//            'userId' => $userId,
//            'couponCode' => $couponCode,
//            'createdAt' => date("Y-m-d"),
//            'used' => 0,
//        ]);
        DB::insert('insert into couponEvents (userId,couponCode,createdAt,used) values(?,?,?,?)', [$userId,
            $couponCode, date("Y-m-d"), 0
        ]);
    }


    static public function getLastCoupon($userId)
    {
        $targetCoupon = DB::table('couponEvents')
            ->where('userId', "=", $userId)
            ->where('createdAt', "=", date('Y-m-d'))
            ->where('used', '=', 0)
            ->orderBy('id', 'desc')
            ->first();

        return self::checkCoupon($targetCoupon);
    }

    static private function checkCoupon($targetCoupon)
    {
        // check if entered coupon is valid
        $allCoupons = self::getCoupons();
        $valid = false;
        foreach ($allCoupons as $coupon) {
            if (isset($targetCoupon)) {
                if ($coupon->codeCoupon == $targetCoupon->couponCode) {
                    $valid = true;
                    //\DB::table('couponEvents')->where('id', '=', $targetCoupon->id)->update(['used' => 1]);
                    \DB::update('update couponEvents set used=? where id=?', [1, $targetCoupon->id]);
                };
            }
        }

        return $valid ? $targetCoupon : "";
    }

    static public function checkCouponCode($targetCoupon)
    {
        $result = DB::table('coupons')
            ->where('power', "=", 1)
            ->where('dateCoupon', "<=", date('Y-m-d'))
            ->where('dateEndCoupon', ">=", date('Y-m-d'))
            ->where('codeCoupon', '=', $targetCoupon)
            ->first();
        return $result != null ? $result : "";
    }
}