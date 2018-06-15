<?php

namespace App\Http\Controllers;

use App\Models\Aliexpress\Aliexpress;
use App\Models\Cart;
use App\Models\Checkout;
use App\Models\Ebay\EbayCategories;
use App\User;
use Illuminate\Support\Facades\Input;
use Log;
use App\Util;

class CheckoutController extends Controller
{
    private $model_ebay;
    private $model_ali;

    function __construct()
    {
        parent::__construct();
    }


    public function index()
    {
        // get products from session
        $products = Cart::getProducts();
        $products = Cart::checkNextShipping($products);

        if (env('APP_ENV') == \Config::get('enums.env.LOCAL')) {
            $products = Checkout::refreshProductsPrice($products);
        }

        $targetCoupon = isset($_COOKIE['coupon']) ? $_COOKIE['coupon'] : null;

        $productsDiscount = Checkout::getDiscountPrices($targetCoupon, $products);
        $usedCoupon = Checkout::checkCouponCode($targetCoupon);
        $page = 'checkout';
        // get data for iframe
        $productAmount = count($products);//set amount

        if ($productAmount > 0) $products = Cart::tidyProducts($products);
        $coupons = Checkout::getCoupons();

        $jsData["products"] = $products;
        $jsData["usedCoupon"] = $usedCoupon;
        $jsData = Util::includeJsWithData("jsData", $jsData);

        return view("user.checkout", [
            'user' => User::getBy("id", $_SESSION['user']->id),
            'products' => $products,
            'coupons' => $coupons,
            'page' => $page,
            'usedCoupon' => $usedCoupon,
            'productsDiscount' => $productsDiscount,
            'jsData' => $jsData,
        ]);
    }

    public function success()
    {

        $userId = $_GET['usr_id'];

        // get last summary of this user
        $summary = Checkout::getLastSummary($userId);


//         get last coupon used by this user
        //$coupon = Checkout::getLastCoupon($userId);

        $targetCoupon = isset($_COOKIE['coupon']) ? $_COOKIE['coupon'] : null;
        $coupon = Checkout::checkCouponCode($targetCoupon);
        setcookie('coupon', " ", time() - 3600 * 24 * 365, "/");
//         add data about coupon to summary
        if ($coupon != "" && count($coupon) > 0) {
            Checkout::updateSummary($userId, $coupon->codeCoupon);
        }

        try {
            $summaryId = $summary->summaryId;
            $totalPaid = $summary->summaryTotalPriceOrder;
        } catch (\Exception $exception) {
            $summaryId = "_";
            $totalPaid = 0;
        }
        $_SESSION['user'] = User::getBy('id', $userId);


        $get_product_ordersummary = \DB::table('orderDeatils')->where('orderSummaryId', $summary->summaryId)->select('orderProductId')->first();

        $similar_product = $this->get_similar_products($get_product_ordersummary);

        // update products in cart to be marked as ordered
        Checkout::updateCart($userId, $summaryId);

        return view("user.checkout.success", [
            "data" => Input::get(),
            'totalPaid' => $totalPaid,
            'summaryId' => $summaryId,
            'similar_products' => $similar_product
        ]);

    }

    public function fail()
    {
        return view("user.checkout.fail", [
            "data" => Input::get()
        ]);
    }

    public function process()
    {
        $lowProfile = $_GET['lowprofilecode'];

        if (env('APP_ENV') == \Config::get('enums.env.LIVE')) {
            $vars = array(
                'TerminalNumber' => '39555',
                'LowProfileCode' => $lowProfile,
                'UserName' => 'b273baMOpSNgw7iqeFnM'
            );
        } else {
            $vars = array(
                'TerminalNumber' => '1000',
                'LowProfileCode' => $lowProfile,
                'UserName' => 'barak9611'
            );
        }

        // get response data from payment system about current payment
        $output = Checkout::processPaymentInterface($vars);
//        Log::info("Result is " . $result);
////        Log::info("Result is " . print_r($output, true));
//
//        Log::info("Result: " . json_encode($output));
//        Log::info("Error: " . json_encode($error));

        if ($output['OperationResponse'] == '0') {
            $returnValue = $output['ReturnValue'];
            $returnValues = explode('||', $returnValue);

            $userId = $returnValues[0];
            $orderTotalCost = round($returnValues[1], 2);
            $ip = $returnValues[2];
            $device = $returnValues[3];
            $notice = $returnValues[4];
            $affiliate = $returnValues[5];
            $couponCode = $returnValues[6];

            $creditNumber = $output['ExtShvaParams_CardNumber5'];

            $totalPaid = $output['ExtShvaParams_Sum36'] / 100;

            // TODO: fetch from checkout page (or no?)
            $orderComment = "";
            // $couponCode = "0";
            if ($totalPaid != 0) {
                $confirmCode = $output['ExtShvaParams_ApprovalNumber71'];

                $products = Cart::getProducts($userId);

                //$products = Checkout::applyDiscountForProducts($coupon, $products);

                // if there is no products in cart - stop further actions at once
                if (count($products) == 0) return;

                // add summary to db
                $summaryId = Checkout::addSummary($userId, $orderTotalCost, $totalPaid,
                    $creditNumber, $confirmCode, $orderComment,
                    $ip, $couponCode, $device, $affiliate);

                foreach ($products as $product) {
                    if ($product->sitename == "next") {
                        $productUrl = $product->productID;
                    } else {
                        $productUrl = '';
                    }
                    $araray = explode("/", $product->productUrl);

                    $originId = end($araray);
                    $originId = explode("?", $originId);
                    $originId = $originId[0];

                    $categoryId = 1;
                    $splitter = "?categoryID=";
                    // remove category id if there is one
                    if (strpos($originId, $splitter) != false) {
                        $data = explode($splitter, $originId);
                        $originId = $data[0];
                        $categoryId = $data[1];
                    }

                    // add products from cart to checkout data
                    Checkout::addOrderDetail(
                        $userId, $originId,
                        $summaryId, $categoryId, $product, $productUrl
                    );
                }

                // add some shipping data
                $user = User::getBy('id', $userId);
                Checkout::addShippingDetails($userId, $summaryId, $notice, $user);

            } else {
                header('Location: https://www.chipi.co.il');
            }
        } else {
            Log::error("Checkout response is not 0 ");
        }
    }

    public function get_similar_products($products_summary)
    {

        $this->model_ebay = new EbayCategories();
        $this->model_ali = new Aliexpress();
        $similar_product_checkout = array();

        foreach ($products_summary as $product) {

            //  if($product->site == 'ebay'){
            $similar_product_ebay = $this->model_ebay->getSimilarProduct($product, 10);
            if (isset($similar_product_ebay)) {
                foreach ($similar_product_ebay as $s_product) {
                    if (isset($s_product->itemRecommendations)) {
                        foreach ($s_product->itemRecommendations->item as &$item) {
                            if (isset($item->buyItNowPrice->__value__) && $item->buyItNowPrice->__value__ != '0.00') {
                                $price = Util::replacePrices($item->buyItNowPrice->__value__);
                                $item->buyItNowPrice->__value__ = $price;
                            }
                            if (isset($item->currentPrice->__value__) && $item->currentPrice->__value__ != '0.00') {
                                $price = Util::replacePrices($item->currentPrice->__value__);
                                $item->currentPrice->__value__ = $price;
                            }
                            $item->sitename = 'ebay';
                            $item->product_base = '/ebay/product';
                            array_push($similar_product_checkout, $item);
                        }
                    }
                }
            }
            //    }

//          if($product->sitename == 'aliexpress'){
//               $similar_product_ali = $this->model_ali->getSimilarProduct($product->productID);
//                $dec_similar_product_ali = json_decode($similar_product_ali);
//                    foreach ($dec_similar_product_ali->result->products as $sim_ali_prod) {
//                            $new_products = new \stdClass();
//                            $new_products->itemId = $sim_ali_prod->productId;
//                            $new_products->title = $sim_ali_prod->productTitle;
//                            $url_html = "/aliexpress/product/" . explode("/", $sim_ali_prod->productUrl)[4] . "/" . explode("?",explode("/", $sim_ali_prod->productUrl)[5])[0];
//                            $new_url = str_replace('.html', "", $url_html);
//                            $new_products->viewItemURL = $new_url;
//                            $new_products->imageURL = $sim_ali_prod->imageUrl;
//                            $new_products->salePrice = Util::replacePrices($sim_ali_prod->salePrice);
//                            $new_products->sitename = 'aliexpress';
//                            $new_products->product_base = '/aliexpress/product';
//                            $newProducts[] = $new_products;
//                 }
//
//                 if(isset($newProducts)){
//                     $similar_product_checkout = $similar_product_checkout + $newProducts;
//                 }
//            }
        }

        return $similar_product_checkout;
    }
}