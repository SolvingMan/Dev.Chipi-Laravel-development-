<?php

namespace App\Models\Ebay;

use App\Http\API\EbayAPI;
use App\Marketing;
use App\Util;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Stichoza\GoogleTranslate\TranslateClient;

/**
 * App\Ebay
 *
 * @mixin \Eloquent
 */
class EbayCategories extends Model
{
    protected $table = "ebayCats";
    protected $subTable = "ebaySubcats";
    protected $subSubTable = "ebaySubSubcats";
    private $ebayAPI;

    public function __construct(/*$rate = 1,*/
        array $attributes = [])
    {
        parent::__construct($attributes);
        //$this->rate = $rate;
        $this->ebayAPI = new EbayAPI();
    }

    public function getCategories()
    {
        $categories = EbayCategories::all();
        return $this->getChildrenCategories($categories);

    }

    public function getCategory($categoryID)
    {
        $category = EbayCategories::where("catId", "=", $categoryID)->get();
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
                    ->where("ebaySubcatsId", "=", $id)
                    ->get();

                $categories[$catKey]['sub'][$subKey]->sub = $subSub;
            }
        }

        return $categories;
    }

//    public
//    function getTopSellingProducts()
//    {
//        $topSellingProducts = $this->ebayAPI->getTopSellingProducts();
//        foreach ($topSellingProducts as $product) {
//            $newProduct = new \stdClass();
//            $newPrice = Util::replacePrices($product->priceRangeMax->__value__);
//            $newProduct->price = $newPrice;
//            $newProduct->productId = $product->productId->__value__;
//            $newProduct->imageURL = $product->imageURL;
//            $newProduct->productURL = $product->productURL;
//            $result[] = $newProduct;
//        }
//        return $result;
//    }
//
//    public
//    function getMostWatchedItems($categoryID)
//    {
//        $result = [];
//        $mostWatchedItems = $this->ebayAPI->getMostWatchedItems($categoryID);
//        foreach ($mostWatchedItems as $product) {
//            $newProduct = new \stdClass();
//            $newPrice = Util::replacePrices($product->buyItNowPrice->__value__);
//            $newProduct->price = $newPrice;
//            $newProduct->productId = $product->itemId;
//            $newProduct->imageURL = $product->imageURL;
//            $newProduct->productURL = $product->viewItemURL;
//            $newProduct->productTitle = $product->title;
//            $result[] = $newProduct;
//        }
//        return $result;
//    }

    public function getProductsByCategory($categoryID, $entriesPerPage = 10, $pageNumber = 1, $filterData = array(), $sortOrder = null)
    {
        $result = new \stdClass();
        unset($filterData["page"]);
        unset($filterData["sortOrder"]);
        unset($filterData["searchKeywords"]);
        $filterDataResult = null;
        foreach ($filterData as $filterTitle => $filterName) {
            $filterTitle = str_replace("_", " ", $filterTitle);
            $filterTitle = urlencode($filterTitle);
            $filterName = urlencode($filterName);
            $filterDataResult[$filterTitle] = $filterName;
        }
        $productsByCategory = $this->ebayAPI->getProductsByCategory($categoryID, $entriesPerPage, $pageNumber, $filterDataResult, $sortOrder);

        $result->totalEntries = $productsByCategory->paginationOutput[0]->totalEntries[0];
        $result->totalPages = $productsByCategory->paginationOutput[0]->totalPages[0];
        if ($productsByCategory->searchResult[0]->{"@count"} > 0) {
            foreach ($productsByCategory->searchResult[0]->item as $product) {
                $newPrice = Util::replacePrices($product->sellingStatus[0]->currentPrice[0]->__value__,
                    $product->sellingStatus[0]->currentPrice[0]->{"@currencyId"});
                $newProduct = new \stdClass();
                if (isset($product->discountPriceInfo) && isset($product->discountPriceInfo[0]->originalRetailPrice)) {
                    $newProduct->oldPrice = Util::replacePrices($product->discountPriceInfo[0]->originalRetailPrice[0]
                        ->__value__, $product->discountPriceInfo[0]->originalRetailPrice[0]->{"@currencyId"});
                    $newProduct->discount = floor((($newProduct->oldPrice / $newPrice) - 1) * 100);
                }
                $newProduct->price = $newPrice;
                if (isset($product->galleryURL[0])) {
                    $newProduct->imageUrl = $product->galleryURL[0];
                } else {
                    $newProduct->imageUrl = null;
                }
                $newProduct->productTitle = $product->title[0];
                $newProduct->productUrl = $product->viewItemURL[0];
                $newProduct->productId = $product->itemId[0];
                $result->products[] = $newProduct;
            }
        } else {
            $result->products = null;
        }

        return $result;
    }

    public
    function getProduct($productID)
    {
        $product = $this->ebayAPI->getProduct($productID);
        $shipping = $this->ebayAPI->getShipping($productID);
        $newPrice = Util::calculate_main_price($product->CurrentPrice->Value, $product->CurrentPrice->CurrencyID);
        $product->shekelPrice = $newPrice;
        $product->haveImportCharge = false;

        //dd($product);
        if ($shipping->Ack == "Success" && isset($shipping->ShippingCostSummary->ShippingServiceCost->Value)) {
            $shippingServiceCost = (isset($shipping->ShippingCostSummary->ShippingServiceCost->Value) ?
                Util::calculate_shipping_price(floatval($shipping->ShippingCostSummary->ShippingServiceCost->Value),
                    $shipping->ShippingCostSummary->ShippingServiceCost->CurrencyID) : 0);
//            $shippingServiceAdditionalCost = (isset($shipping->ShippingDetails->InternationalShippingServiceOption->ShippingServiceAdditionalCost) ?
//                Util::replacePrices(floatval($shipping->ShippingDetails->InternationalShippingServiceOption->ShippingServiceAdditionalCost)) : 0);
            $importCharge = (isset($shipping->ShippingCostSummary->ImportCharge->Value) ?
                Util::calculate_shipping_price(floatval($shipping->ShippingCostSummary->ImportCharge->Value),
                    $shipping->ShippingCostSummary->ImportCharge->CurrencyID) : 0);

            $newShekelShippingServiceCost = $shippingServiceCost + /*$shippingServiceAdditionalCost +*/
                $importCharge;

            if ($importCharge > 0) {
                $product->haveImportCharge = true;
            }

            $newShippingServiceCost =
                (isset($shipping->ShippingCostSummary->ShippingServiceCost->Value) ?
                    floatval($shipping->ShippingCostSummary->ShippingServiceCost->Value) : 0) +
                /*(isset($shipping->ShippingDetails->InternationalShippingServiceOption->ShippingServiceAdditionalCost) ?
                    floatval($shipping->ShippingDetails->InternationalShippingServiceOption->ShippingServiceAdditionalCost) : 0) +*/
                (isset($shipping->ShippingCostSummary->ImportCharge->Value) ?
                    floatval($shipping->ShippingCostSummary->ImportCharge->Value) : 0);
            $product->shippingServiceCost = $newShippingServiceCost;
            $product->shekelShippingServiceCost = $newShekelShippingServiceCost;
            $product->shippingService = isset($shipping->ShippingCostSummary->
                ShippingServiceName) ? $shipping->ShippingCostSummary->
            ShippingServiceName : "-";
            $shippingToIsrael = $this->ebayAPI->isShippingToIsrael($product);
            $product->shippingToIsrael = $shippingToIsrael;
        } else {
//            Marketing::sendMail("info@chipi.co.il","problem with shipping for ".$product->ItemID);
            $product->shekelShippingServiceCost = '-';
            $product->shippingService = '-';
            $product->shippingToIsrael = false;
        }
        $itemSpecificsHebrew = [];

        if (isset($product->MinimumToBid->Value)) {
            $product->shippingToIsrael = false;
        }
        $stringSpecEng = "";
        if (isset($product->ItemSpecifics)) {
            foreach ($product->ItemSpecifics->NameValueList as $specific) {
                $stringSpecEng .= $specific->Value[0] . ' . ';
                $stringSpecEng .= $specific->Name . ' ! ';
            }
            $stringSpecHebrew = Util::translateEnglishToHebrew($stringSpecEng);
            $arrayHebrew = explode("!", $stringSpecHebrew);
            foreach ($arrayHebrew as $item) {
                $itemArray = explode(".", $item);
                $hebrew = new \stdClass();
                if (isset($itemArray[0])) {
                    $hebrew->value = $itemArray[0];
                } else $hebrew->value = "";
                if (isset($itemArray[1])) {
                    $hebrew->name = $itemArray[1];
                } else  $hebrew->name = "";
                $itemSpecificsHebrew[] = $hebrew;
            }
            if ($itemSpecificsHebrew[count($itemSpecificsHebrew) - 1]->value == "") {
                unset($itemSpecificsHebrew[count($itemSpecificsHebrew) - 1]);
            }
            $product->ItemSpecificsHebrew = $itemSpecificsHebrew;
        }

        if (isset($product->Variations)) {
            for ($i = 0; $i < count($product->Variations->Variation); $i++) {
                $product->Variations->Variation[$i]->StartPrice->OriginalValue = $product->Variations->Variation[$i]->StartPrice->Value;
                $product->Variations->Variation[$i]->StartPrice->Value = Util::calculate_main_price($product->Variations
                    ->Variation[$i]->StartPrice->Value, $product->Variations
                    ->Variation[$i]->StartPrice->CurrencyID);
                $product->Variations->Variation[$i]->Quantity = $product->Variations->Variation[$i]->Quantity
                    - $product->Variations->Variation[$i]->SellingStatus->QuantitySold;
                for ($j = 0; $j < count($product->Variations->Variation[$i]->VariationSpecifics->NameValueList); $j++) {
                    $product->Variations->Variation[$i]->VariationSpecifics->NameValueList[$j]->Value[0] = str_replace("\"", " ", $product->Variations->Variation[$i]->VariationSpecifics->NameValueList[$j]->Value[0]);
                }
            }
            for ($i = 0; $i < count($product->Variations->VariationSpecificsSet->NameValueList); $i++) {
                for ($j = 0; $j < count($product->Variations->VariationSpecificsSet->NameValueList[$i]->Value); $j++) {
                    $product->Variations->VariationSpecificsSet->NameValueList[$i]->Value[$j] = str_replace("\"", " ", $product->Variations->VariationSpecificsSet->NameValueList[$i]->Value[$j]);
                }
            }
        }
        $product->ConditionName = $this->hebrewConditionName(isset($product->ConditionID) ? $product->ConditionID : "");
        $product->Quantity = $product->Quantity - $product->QuantitySold;
//dd($product->Variations);
        return $product;
    }

    public function getDescription($productID)
    {
        $product = $this->ebayAPI->getProduct($productID);
        $product->Description = preg_replace('/href=\".+?\"/s', " ", $product->Description);
        $product->Description = preg_replace('/target=\"\_blank\"/s', " ", $product->Description);
        return $product->Description;
    }

    function hebrewConditionName($condition)
    {
        switch ($condition) {
            case 1000:
                $result = "מוצר חדש עם תגיות";
                break;
            case 1500:
                $result = "מוצר חדש ללא תגיות";
                break;
            case 3000:
                $result = "משומש";
                break;
            case 1750:
                $result = "חדש עם פגמים";
                break;
            case 2000:
                $result = "שופץ על ידי היצרן";
                break;
            case 2500:
                $result = "שופץ על ידי המוכר";
                break;
            case 4000:
                $result = "משומש במצב מעולה";
                break;
            case 5000:
                $result = "משומש במצב טוב";
                break;
            case 6000:
                $result = "משומש במצב לא רע";
                break;
            case 7000:
                $result = "לחלקי חילוף";
                break;
            default:
                $result = "";
        }
        return $result;
    }

    public
    function getProductsByKeywords($keywords, $entriesPerPage, $page, $filterData, $sortOrder)
    {
        $result = new \stdClass();
        $keywords = str_replace(" ", "%20", $keywords);
        $keywords = str_replace("&", "%26", $keywords);
        unset($filterData["page"]);
        unset($filterData["sortOrder"]);
        unset($filterData["searchKeywords"]);
        unset($filterData["utm_source"]);
        unset($filterData["utm_medium"]);
        unset($filterData["utm_campaign"]);

        $filterDataResult = null;
        foreach ($filterData as $filterTitle => $filterName) {
            $filterTitle = str_replace("_", " ", $filterTitle);
            $filterTitle = urlencode($filterTitle);
            $filterName = urlencode($filterName);
            $filterDataResult[$filterTitle] = $filterName;
        }
        $productsByCategory = $this->ebayAPI->getProductsByKeywords($keywords, $entriesPerPage, $page, $filterDataResult, $sortOrder);

        //dd($productsByCategory);
        //dd($productsByCategory);
        if ($productsByCategory->ack[0] !== "Failure" && $productsByCategory->searchResult[0]->{"@count"} > 0) {
            $result->totalEntries = $productsByCategory->paginationOutput[0]->totalEntries[0];
            $result->totalPages = $productsByCategory->paginationOutput[0]->totalPages[0];
            foreach ($productsByCategory->searchResult[0]->item as $product) {
                $newPrice = Util::replacePrices($product->sellingStatus[0]->currentPrice[0]->__value__,
                    $product->sellingStatus[0]->currentPrice[0]->{"@currencyId"});
                $newProduct = new \stdClass();
                if (isset($product->discountPriceInfo) && isset($product->discountPriceInfo[0]->originalRetailPrice)) {
                    $newProduct->oldPrice = Util::replacePrices($product->discountPriceInfo[0]->originalRetailPrice[0]
                        ->__value__, $product->discountPriceInfo[0]->originalRetailPrice[0]->{"@currencyId"});
                    $newProduct->discount = floor((($newProduct->oldPrice / $newPrice) - 1) * 100);
                }
                $newProduct->price = $newPrice;
                if (isset($product->galleryURL[0])) {
                    $newProduct->imageUrl = $product->galleryURL[0];
                } else {
                    $newProduct->imageUrl = null;
                }
                $newProduct->productTitle = $product->title[0];
                $newProduct->productUrl = $product->viewItemURL[0];
                $newProduct->productId = $product->itemId[0];
                $result->products[] = $newProduct;
            }
        } else {
            $result->products = null;
        }

        return $result;
    }

    public
    function getFiltersForTitle($filters)
    {
        $filtersString = "";
        foreach ($filters as $filterName => $filter) {
            $filtersString .= $filterName . " ";
            foreach ($filter as $filterValue) {
                $filtersString .= $filterValue . " ";
            }
        }
        return Util::translateEnglishToHebrew($filtersString);
    }

    public
    function getFiltersForCategory($categoryID)
    {
        $db = \DB::table("filters")->where("catId", "=", $categoryID)->get();
        $result = [];
        foreach ($db as $dbFilter) {
            $filter = new \stdClass();
            $filter->filterNameEn = $dbFilter->filterNameEn;
            $filter->filterNameHeb = $dbFilter->filterNameHeb;
            $filter->filterValueEn = explode(",", $dbFilter->filterValueEn);
            $filter->filterValueHeb = explode(",", $dbFilter->filterValueHeb);
            $result[] = $filter;
        }
        return $result;
    }

    public function getFiltersForSearch($keyword)
    {
        $keyword = str_replace(" ", "%20", $keyword);
        $keyword = str_replace("&", "%26", $keyword);
        return $this->ebayAPI->getFiltersForSearch($keyword);
    }

    public function getBreadCramb($categoryID)
    {
        $record = \DB::table("ebaySubSubcats")->where("ebayId", "=", $categoryID)->get()->first();
        //dd($record);
        //dd($categoryID);
        if ($record) {
            $result["subSubCategory"] = $record->subSubcatName;
            $subCategory = \DB::table("ebaySubcats")->where("subcatId", "=", $record->ebaySubcatsId)
                ->select("subcatName")->first();
            $category = \DB::table("ebayCats")->where("catId", "=", $record->ebayCatsId)
                ->select("catId", "catName")->first();

            $result["subCategory"] = $subCategory->subcatName;
            $result["category"] = $category->catName;
            $result["category_id"] = $category->catId;
        } else {
            $result = null;
        }

        return $result;
    }


    public function getOneDayDeals()
    {

        $result = [];

        $oneDayDeals = \DB::table('products')->orderBy('itemId', 'desc')->distinct()
            ->select('itemId', 'title', 'picture', 'price')->skip(10)->take(30)->get();

        foreach ($oneDayDeals as $oneDayDeal) {
            $newProduct = new \stdClass();
            $newPrice = Util::replacePrices($oneDayDeal->price);
            $newProduct->discount = ceil(mt_rand(30, 50));
            $newProduct->originalPrice = ceil($newPrice * ($newProduct->discount / 100 + 1));
            $newProduct->price = $newPrice;
            $newProduct->itemId = $oneDayDeal->itemId;
            $newProduct->title = str_replace("/", "", $oneDayDeal->title);
            $newProduct->image = $oneDayDeal->picture;
            $result[] = $newProduct;
        }
        return $result;
    }

    public function getSimilarCategory($categoryId)
    {
        $categoryParentId = \DB::table($this->subSubTable)->where("ebayId", "=", $categoryId)->first();
        $similarCategories = \DB::table($this->subSubTable)->where("ebaySubcatsId", "=", $categoryParentId
            ->ebaySubcatsId)->get();

        return $similarCategories;
    }

    public function getSimilarProduct($id_product, $numberItems)
    {
        return $this->ebayAPI->getSimilarProduct($id_product, $numberItems);
    }

    public function getFeedbackItem($id_product)
    {
        return $this->ebayAPI->getFeedback($id_product);
    }
}
