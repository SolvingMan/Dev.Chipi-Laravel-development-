<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 21.03.2017
 * Time: 12:54
 */

namespace App\Http\Controllers;

use App\Http\API\AliexpressAPI;
use App\Http\API\AsosAPI;
use App\Http\API\EbayAPI;
use App\Http\API\NextAPI;
use App\Models\Cart;
use App\Models\Ebay\EbayCategories;
use App\Models\Next\NextModel;
use App\Util;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\In;
use Stichoza\GoogleTranslate\TranslateClient;

class MainController extends Controller
{
    function __construct()
    {
        parent::__construct();
    }


    protected function index()
    {
        //dd(Input::get());
        //$exchangeRate = Util::getExchangeRate($baseCurrency, $endCurrency);
        $model = new EbayCategories();
        $nextAPI = new NextAPI();
        $aliexpressAPI = new AliexpressAPI("");
        $ebayAPI = new EbayAPI();
        $nextModel = new NextModel();
        $asosAPI = new AsosAPI();
        $id = "282534754296";
        $url = "https://gw.api.alibaba.com/openapi/param2/2/portals.open/api.getOrderStatus/16800?appSignature=QiXwKzo2W6P&orderNumbers=86459966757363";
//        $result = $ebayAPI->getShippingOrigin(291857291952);
        //$this->getCsvFile();
        //$result = $asosAPI->getContent("https://flashdeals.aliexpress.com/en.htm?spm=2114.11010108.21.2.35f7175HaKIzq#5041187");
        //$result = json_decode($result);

        // echo "id: " . $id;
//        $table = 'ebaySubSubcats';
//        $enField = "subSubcatEnglish";
//        $esField = "subSubcatSpanish";
//        $idField = "subSubcatId";
//        $result = DB::table($table)->get();
////        dd($result);
//        foreach ($result as $cat) {
////            echo $cat->catEnglish;
////            echo "<br>";
//            $tc = new TranslateClient("en", "es");
//            $espWord = $tc->translate($cat->{$enField});
//
//            DB::table($table)->where($idField, $cat->{$idField})->update([
//                $esField => $espWord
//            ]);

//            dd();

//        }
//
//        echo '<pre>';
        dd("hello");
//
//        echo '</pre>';
//        return view('main.show', [
//            'products' => $result->gpsProductDetails,
//        ]);
    }

//    public function getCsvFile()
//    {
//        $string = file_get_contents("D:\streets&cityies.csv");
//        $convertString = iconv("ISO-8859-8", "UTF-8", $string);
//        $array = explode("\r\n", $convertString);
//        $lastCityId = "";
//        $lastCityName = "";
//        foreach ($array as $string) {
//            $stringArray = explode(",", $string);
//            if (isset($stringArray[1]) && isset($stringArray[0])) {
//                if (trim($stringArray[0]) == $lastCityName) {
//                    \DB::table("streets")->insert(['streetName' => trim($stringArray[1]), 'cityID' => $lastCityId]);
//                } else {
//                    $lastCityId = \DB::table("cities")->insertGetId(['cityName' => trim($stringArray[0])]);
//                    $lastCityName = trim($stringArray[0]);
//                    \DB::table("streets")->insert(['streetName' => trim($stringArray[1]), 'cityID' => $lastCityId]);
//                }
//            }
//        }
//
//    }

#"{\"\\u05e6\\u05d1\\u05e2:\":\"Khaki\",\"\\u05d2\\u05d5\\u05d3\\u05dc:\":\"XL\",\"numberVariation\":\"365458,100014065\"}"
    protected function rules()
    {
        $rules = \DB::table("pages")->where("pageId", "=", 1)->get()->first();
        return view('main.rules', [
            "rules" => $rules,
        ]);
    }

    protected function whoWeAre()
    {
        $whoWeAre = \DB::table("pages")->where("pageId", "=", 2)->get()->first();
        return view('main.whoWeAre', [
            "whoWeAre" => $whoWeAre
        ]);
    }

    protected function customerService()
    {
        $qa = \DB::table("qa")->get();
        //dd($qa);
        return view('main.questionanswer', [
            "qa" => $qa,
        ]);
    }

    protected function contactUs()
    {
        //dd($_SESSION['user']->id);
        $orderHistory = Cart::getCurrentHistory();
        $existTicket = \DB::table("customerService")->where([["userId", "=", $_SESSION['user']->id], ["status", "<>", 5],
            ["ordernumber", "<>", "0"]])->select(['ordernumber'])->get();
        $jsData = Util::includeJsWithData("jsData", $existTicket);

        //dd($orderHistory);
        return view('main.contactus', [
            "orderHistory" => $orderHistory,
            "jsData" => $jsData,
        ]);
    }

    protected function contactUsPost(\Request $request)
    {
//        if (Input::get('g-recaptcha-response') == null) {
//            return redirect('/main/contactus')
//                ->withInput([
//                    'error' => Lang::get("general.CAPTCHA_field_required")
//                ]);
//        }
        if (Input::has("ordernumber")) {
            $orderNumber = Input::get("ordernumber");
        } else {
            $orderNumber = 0;
        }

        \DB::table("customerService")->insert([
            'userId' => Input::get("userID"),
            'department' => Input::get("department"),
            'ordernumber' => $orderNumber,
            'message' => Input::get("message"),
            'date' => date("Y-m-d"),
            'time' => date("H:i:s"),
            'status' => '0',
        ]);

        $jsDataSuccess = Util::includeJsWithData("jsDataSuccess", "success");
        return redirect()->back()->withInput([
            'success' => Lang::get('general.request_submitted'),
            'jsDataSuccess' => $jsDataSuccess
        ]);
    }

    protected function questionAnswer()
    {

        $qa = \DB::table("qa")->get();
        //dd($qa);
        return view('main.questionanswer', [
            "qa" => $qa,
        ]);
    }

    protected function zipDeterminer()
    {
        return view('main.zipDeterminer', [
            'zipDeterminer' => true,
            'title' => "איתור מיקוד , חיפוש מיקוד בישראל - צ'יפי קניות ברשת",
            'description' => "איתור מיקוד בישראל - צ'יפי הפלטפורמה המובילה לרכישה מאתרי הסחר העולמיים בעברית",
        ]);
    }

//    protected function checkProducts()
//    {
//        \DB::table("products")->truncate();
//        $categories = ['tech/cell-phones', 'tech/laptops-netbooks', 'tech/cameras-photo', 'tech/ipads-tablets-ereaders',
//            'tech/video-games-consoles', 'tech/phone-cases-accessories', 'tech/vehicle-electronics-gps', 'tech/memory-drives-storage',
//            'tech/headphones-portable-audio', 'tech/lenses-filters', 'tech/more-consumer-electronics', 'tech/computer-accessories',
//            'fashion/watches', 'fashion/fine-jewelry', 'fashion/beauty', 'fashion/health', 'fashion/mens-shoes-accessories',
//            'fashion/womens-shoes-accessories', 'fashion/handbags-and-accessories', 'fashion/mens-clothing', 'fashion/womens-clothing',
//            'fashion/kids-stuff', 'fashion/engagement-wedding', 'fashion/more-clothing-shoes-accessories', 'fashion/skin-bath-body',
//            'fashion/fragrances', 'fashion/makeup', 'fashion/hair-care-salon', 'fashion/other-health-beauty',
//            'fashion/more-jewelry'];
//        $category = Input::get('category');
//        $ebayAPI = new EbayAPI();
//        for ($i = 1; $i < count($categories); $i++) {
//            $productsByCategory = $ebayAPI->getDealsTest($category);
//            foreach ($productsByCategory->items as $product) {
//                $productInfo = $ebayAPI->getProduct($product->viewModel->listingSummary->listingId);
//                $shippingInfo = $ebayAPI->getShipping($product->viewModel->listingSummary->listingId);
//                if ($ebayAPI->isShippingToIsrael($productInfo) && $shippingInfo->Ack == "Success") {
//                    \DB::table("products")->insert([
//                        'itemId' => $product->viewModel->listingSummary->listingId,
//                        'picture' => $productInfo->PictureURL[0],
//                        'title' => $productInfo->Title,
//                        'price' => $productInfo->CurrentPrice->Value,
//                        'shippingPrice' => $shippingInfo->ShippingCostSummary->ShippingServiceCost->Value,
//                        'importPrice' => (isset($shippingInfo->ShippingCostSummary->ImportCharge->Value)) ?
//                            $shippingInfo->ShippingCostSummary->ImportCharge->Value : 0,
//                        'categoryId' => $productInfo->PrimaryCategoryID,
//                        'feedbackScore' => $productInfo->Seller->FeedbackScore,
//                    ]);
//                }
//            }
//        }
//    }
}