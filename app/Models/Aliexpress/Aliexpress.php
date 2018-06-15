<?php

namespace App\Models\Aliexpress;

use App\Util;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Cache;
use App;
use Config;
use App\Http\API\AliexpressAPI;

class Aliexpress extends Model
{
    protected $products;
    public $table = "aliexpressCats";
    public $subTable = "aliexpressSubcats";
    public $subSubTable = "aliexpressSubSubcats";
    public $starter;
    public $productURLBase;
    public $aliAPI;


    public static $categories;

    function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // base for item url
        $this->starter = App::getLocale() == "en" ? "www" : App::getLocale();
        $this->productURLBase = "https://{$this->starter}.aliexpress.com/item/";
        $this->aliAPI = new AliexpressAPI();
    }

    public static function getCategories()
    {
//        if (Cache::get('categories') == null) {
        //Cache::put('categories', (new Aliexpress())->getCategoriesList(), Config::get('cache.storeAliCategoriesList'));
//        }

        return (new Aliexpress())->getCategoriesList();//Cache::get('categories');
    }

    // tmp solution to retrieve categories from old DB
    public function getCategoriesList()
    {
        $categories = Aliexpress::all();
        return $this->getChildrenCategories($categories);
    }

    public function getCategory($categoryID)
    {
        $category = Aliexpress::where("catId", "=", $categoryID)->get();
        return $this->getChildrenCategories($category);
    }

    private function getChildrenCategories($categories)
    {
        foreach ($categories as $catKey => $catValue) {

            // get all subcategories for this main category
            $sub = DB::table($this->subTable)
                ->where("catName", "=", $categories[$catKey]['catId'])
                ->get();

            $categories[$catKey]['sub'] = $sub;


            foreach ($categories[$catKey]['sub'] as $subKey => $subValue) {

                // this subcat id
                $id = $categories[$catKey]['sub'][$subKey]->subcatId;

                // get all subcategories for this subcategory ( making SUB SUB Categories )
                $subSub = DB::table($this->subSubTable)
                    ->where("aliexpressSubcatsId", "=", $id)
                    ->get();

                $categories[$catKey]['sub'][$subKey]->sub = $subSub;
            }
        }

        return $categories;
    }

    public function getCategoryById($id)
    {
        return \DB::table($this->subSubTable)
            ->where("aliexpressId", "=", $id)
            ->get()[0];
    }

    public function parseCategoryData($data)
    {
        $data = isset($data->result)
            ? $data->result->products
            : $data;

        // convertPrices
        foreach ($data as $key => $product) {
            $data[$key]->originalPrice =
                Util::replacePrices($data[$key]->originalPrice);
            $data[$key]->salePrice =
                Util::replacePrices($data[$key]->salePrice);
//            $data[$key]->imageUrl = $data[$key]->imageUrl. "_100x100.jpg";

            // transform product url to the title/id format
            $beforeHtml = explode(".html", $data[$key]->productUrl)[0];
            $data[$key]->productUrl = explode("/", $beforeHtml)[4] . "/" . explode("/", $beforeHtml)[5];
        }

        return $data;
    }

    public function parseHotProducts($data)
    {
        $data = isset($data->result)
            ? $data->result->products
            : $data;

        // convert Prices and pick a smaller image
        foreach ($data as $key => $product) {

            $data[$key]->salePrice =
                Util::replacePrices($data[$key]->salePrice);
//            $data[$key]->imageUrl = $data[$key]->imageUrl."_100x100.jpg";
//
//            $data[$key]->productUrl = explode(".html", $data[$key]->productUrl)[0];
            $beforeHtml = explode(".html", $data[$key]->productUrl)[0];
            $data[$key]->productUrl = explode("/", $beforeHtml)[4] . "/" . explode("/", $beforeHtml)[5];
        }
        return $data;
    }

    public function parseProductData($data)
    {
        // convert base prices
        $pricesToConvert = ['mainPrice', 'salePrice', 'oldPrice'];
        foreach ($pricesToConvert as $key => $priceName) {
            $data['original_' . $priceName] = $data[$priceName];
            $data[$priceName] = Util::replacePrices($data[$priceName]);
        }

        // convert shipping prices
        foreach ($data['shipping']->freight as $key => $shipping) {
            $data['shipping']->freight[$key]->original_price = $data['shipping']->freight[$key]->price;
            $data['shipping']->freight[$key]->price = Util::replacePrices($data['shipping']->freight[$key]->price);
        }

        // replace all 50x50 mini pics with 100x100 ones
        if ($data['productSKU'] != null) {
            foreach ($data['productSKU'] as $key1 => $sku) {
                foreach ($sku as $key2 => $skuField) {
                    if (isset($skuField['src'])) {
                        $data['productSKU'][$key1][$key2]['src'] = str_replace("50x50", "100x100", $skuField['src']);
                    }
                }
            }
        }

        // convert prices of variations to shekels + add profits
        if ($data['skuArray'] != null) {
            foreach ($data['skuArray'] as $key => $sku) {
                $data['skuArray'][$key]->skuVal->original_skuCalPrice = $data['skuArray'][$key]->skuVal->skuCalPrice;
                $data['skuArray'][$key]->skuVal->skuCalPrice =
                    Util::replacePrices($data['skuArray'][$key]->skuVal->skuCalPrice);
                if (isset($data['skuArray'][$key]->skuVal->actSkuCalPrice)) {
                    $data['skuArray'][$key]->skuVal->original_actSkuCalPrice = $data['skuArray'][$key]->skuVal->actSkuCalPrice;
                    $data['skuArray'][$key]->skuVal->actSkuCalPrice =
                        Util::replacePrices($data['skuArray'][$key]->skuVal->actSkuCalPrice);
                }
            }
        }

        // there is replacing characteristics places ( not working still )
        foreach ($data['characteristics'] as $key => $char) {
            $data['characteristics'][$key][0] =
                trim(str_replace(":", "", $data['characteristics'][$key][0][0]));
            $data['characteristics'][$key][1] =
                trim($data['characteristics'][$key][1][0]);

            if (Util::is_hebrew($data['characteristics'][$key])) {
                $data['characteristics'][$key][2] = "hebflag";
            };
        }
//        dd($data['characteristics']);

        $data['skuArrayJSON'] = Util::objectToArray($data['skuArray']);
        $data['productSkuJSON'] = Util::objectToArray($data['productSKU']);

        return $data;
    }

    public function getBreadCramb($categoryID)
    {
        $record = \DB::table("aliexpressSubSubcats")->where("aliexpressId", "=", $categoryID)->get()->first();
//return $categoryID;
        if ($record) {
            $result["subSubCategory"] = $record->subSubcatName;
            $subCategory = \DB::table("aliexpressSubcats")->where("subcatId", "=", $record->aliexpressSubcatsId)
                ->select("subcatName")->first();
            $category = \DB::table("aliexpressCats")->where("catId", "=", $record->aliexpressCatsId)
                ->select("catId", "catName")->first();
            $result["subCategory"] = $subCategory->subcatName;
            $result["category"] = $category->catName;
            $result["category_id"] = $category->catId;

        } else {
            $result = null;
        }

        return $result;
    }

    public function getSimilarCategory($categoryId)
    {
        $categoryParentId = \DB::table($this->subSubTable)->where("aliexpressId", "=", $categoryId)->first();
        $similarCategories = \DB::table($this->subSubTable)->where("aliexpressSubcatsId", "=", $categoryParentId
            ->aliexpressSubcatsId)->get();

        return $similarCategories;
    }

    public function getSimilarProduct($id_product)
    {
        return $this->aliAPI->getListSimilarProducts($id_product);
    }

    public function getAliDeals($limit, $offset,$widgetId,$phase)
    {
        return $this->aliAPI->getAliDeals($limit, $offset,$widgetId,$phase);
    }

    public function getWidgetPhaseArray()
    {
        return $this->aliAPI->getWidgetPhaseArray();
    }

    public function parseDealsData($data)
    {
        // convertPrices
        foreach ($data->gpsProductDetails as $key => $product) {
            $data->gpsProductDetails[$key]->maxPrice = Util::replacePrices($data->gpsProductDetails[$key]->maxPrice);
            $data->gpsProductDetails[$key]->minPrice = Util::replacePrices($data->gpsProductDetails[$key]->minPrice);
            $data->gpsProductDetails[$key]->oriMinPrice = Util::replacePrices($data->gpsProductDetails[$key]->oriMinPrice);
            $data->gpsProductDetails[$key]->oriMaxPrice = Util::replacePrices($data->gpsProductDetails[$key]->oriMaxPrice);

            // transform product url to the title/id format
            $beforeHtml = explode(".html", $data->gpsProductDetails[$key]->productDetailUrl)[0];

            $data->gpsProductDetails[$key]->productDetailUrl = explode("/", $beforeHtml)[4] . "/" . explode("/", $beforeHtml)[5];
            // $data->gpsProductDetails[$key]->startTime = date("Y-m-d H:i:s",$data->gpsProductDetails[$key]->startTime);
            // $data->gpsProductDetails[$key]->endTime = date("Y-m-d H:i:s",$data->gpsProductDetails[$key]->endTime);
        }
        return $data;
    }

    public function minTime($aliData)
    {
        // dd($aliData);
        $endTime = $aliData->gpsProductDetails[0]->endTime;
        foreach ($aliData->gpsProductDetails as $product) {
            if (isset($product->endTime)) {
                if ($product->endTime < $endTime) {
                    $endTime = $product->endTime;
                }
            }
        }
        return $endTime;
    }
}