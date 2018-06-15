<?php

namespace App\Http\Controllers;

use App\Http\API\EbayAPI;
use App\Market;
use App\Util;
use Illuminate\Http\Request;
use Config;
use Cache;
use App\Models\Ebay\EbayCategories;
use Illuminate\Support\Facades\Input;
use Lang;
use PhpParser\Node\Stmt\Else_;
use Cookie;

class EbayController extends Controller
{
    private $categories;
    private $siteslug;
    private $exchangeRate;
    private $model;
    private $title;
    private $description;
    private $market;
    private $shopName;

    function __construct()
    {
//        session_start();
        parent::__construct();
        $ebayCategories = new EbayCategories();
        $this->market = new Market();
        $this->categories = $ebayCategories->getCategories();
        $this->siteslug = "ebay";
        $this->title = " איביי בעברית , הפלטפורמה המובילה להזמנה מ ebay בעברית - צ'יפי קניות ברשת";
        $this->description = "איביי בעברית , עכשיו זה אפשרי לרכוש בראש שקט,שירות לקוחות בעברית,תשלום ללא כ.א בינלאומי,למעלה ממיליארד מוצרים.";
        $this->middleware(function ($request, $next) {

            // change those to fetchable values (some time later)
            $this->model = new EbayCategories(/*$this->exchangeRate*/);

            return $next($request);
        });
    }


    function index()
    {
        if (Cache::get('ebayMenFashion') == null) {
            Cache::put('ebayMenFashion', $this->model->getProductsByCategory(1059, 40), Config::get('cache.storeEbayIndex'));
        }
        if (Cache::get('ebayWomenFashion') == null) {
            Cache::put('ebayWomenFashion', $this->model->getProductsByCategory(15724, 40), Config::get('cache.storeEbayIndex'));
        }
        if (Cache::get('ebayElectronic') == null) {
            Cache::put('ebayElectronic', $this->model->getProductsByCategory(58058, 40), Config::get('cache.storeEbayIndex'));
        }

        $recommendItems = $this->market->getRecommendProducts(\DB::table('recommendedItems')
            ->where('startDeal', '=', date('Y-m-d'))->get());


        $menFashion = Cache::get('ebayMenFashion');
        $womenFashion = Cache::get('ebayWomenFashion');
        $electronic = Cache::get('ebayElectronic');

        $menFashion = $menFashion->products;
        $womenFashion = $womenFashion->products;
        $electronic = $electronic->products;

        $latestDeals = $this->model->getOneDayDeals();


        $productBaseRoute = "/{$this->siteslug}/product";

        return view("ebay.index", [
            'categories' => $this->categories,
            'menFashion' => $menFashion,
            'womenFashion' => $womenFashion,
            'electronic' => $electronic,
            'latestDeals' => $latestDeals,
            'productBase' => $productBaseRoute,
            'siteslug' => $this->siteslug,
            'title' => $this->title,
            'description' => $this->description,
            'recommendItems' => $recommendItems,
        ]);
    }

    private function generateFilterUrl($getParameters)
    {
        if (count($getParameters) > 0)
            $result = "?";
        else {
            $result = "";
        }
        foreach ($getParameters as $filterTitle => $filterName) {
            $filterTitle = str_replace("_", "+", $filterTitle);
            $filterTitle = str_replace("&", "%26", $filterTitle);
            $filterName = str_replace(" ", "+", $filterName);
            $filterName = str_replace("&", "%26", $filterName);
            $result .= "&" . $filterTitle . "=" . $filterName;
        }
        return $result;
    }

    private function checkedChecked($getParameters)
    {
        unset($getParameters["page"]);
        $result = [];
        foreach ($getParameters as $filterTitle => $filterName) {
            $filterTitle = str_replace("_", " ", $filterTitle);
            $result[$filterTitle] = explode("|", $filterName);
        }
        return $result;
    }

    function description($title, $id, $language)
    {
        $description = $this->model->getDescription($id);

        $description = str_replace("http:", "https:", $description);
        return view("ebay.description", [
            'language' => $language,
            'description' => $description,
        ]);
    }

    function deleteWordsFromString($deletedWords, $string)
    {
        foreach ($deletedWords as $word) {
            $string = str_replace($word, "", $string);
        }
        return $string;
    }

    function search($keyword, $page)
    {
        $parameters = Input::get();

        if (isset($parameters['sortOrder'])) {
            $sort = $parameters['sortOrder'];
        } else {
            $sort = "BestMatch";
        }
        $checkedInput = $this->checkedChecked($parameters);

        if ($keyword) {
            if (Util::is_hebrew($keyword)) {
                $keywordEnglish = Util::translateHebrewToEnglishFromDatabase($keyword);
                Util::storeSearchRequest($keywordEnglish, "ebay", $keyword);
            } else {
                $keywordEnglish = $keyword;
                Util::storeSearchRequest($keywordEnglish, "ebay", $keyword);
            }

            $deletedWords = array(" for ", " in ", " it ", " a ", " the ", " of ", " or ", " I ", " you ", " he ", " me ", " us ", " they ",
                " she ", " to ", " but ", " that ", " this ", " those ", " then ", " with ");

            $keywordEnglish = $this->deleteWordsFromString($deletedWords, $keywordEnglish);

//            $ebayData = $this->model->getProductsByKeywords(
//                $keywordEnglish, 96, $page, $parameters, $sort
//            );
            if (Cache::get('ebaySearch' . $keywordEnglish . '-' . $page . '-' . $sort) == null) {
                Cache::put('ebaySearch' . $keywordEnglish . '-' . $page . '-' . $sort, $this->model->getProductsByKeywords(
                    $keywordEnglish, 96, $page, $parameters, $sort), Config::get('cache.storeEbayIndex'));
            }
            $ebayData = Cache::get('ebaySearch' . $keywordEnglish . '-' . $page . '-' . $sort);
        }
        if (Cache::get('ebayFilters' . $keywordEnglish) == null) {
            Cache::put('ebayFilters' . $keywordEnglish, $this->model->getFiltersForSearch($keywordEnglish),
                Config::get('cache.storeEbayFilters'));
        }
        $filterData = Cache::get('ebayFilters' . $keywordEnglish);

        if ($ebayData->products != null) {
            if ($ebayData->totalPages > 100) {
                $totalPages = 100;

            } else {
                $totalPages = $ebayData->totalPages;
            }
            $totalEntries = $ebayData->totalEntries;
            $filterUrl = $this->generateFilterUrl($parameters);
            $pagesAvailable = Util::getPaginationList($page, $totalPages);
            $paginationResult = Util::getLinksForPagination($pagesAvailable, "search", $keyword, $page, $this->siteslug, $filterUrl);
            $pages = $paginationResult['pages'];
            $pageNext = $paginationResult['pageNext'];
        } else {
            $pages = null;
            $pageNext = null;
            $totalEntries = null;
        }

        $productBaseRoute = "/{$this->siteslug}/product";

        $this->title = "צ'יפי קניות ברשת  איביי בעברית " . $keyword . "חפש ";

        return view("ebay.search", [
            'pagination' => $pages,
            'pageNext' => $pageNext,
            'categories' => $this->categories,
            'productBase' => $productBaseRoute,
            'siteslug' => $this->siteslug,
            'ebayData' => $ebayData->products,
            'checkedInput' => $checkedInput,
            'filterData' => $filterData,
            'title' => $this->title,
            'totalEntries' => $totalEntries,
            'description' => $this->description,
            'keyword' => $keyword,
            'page' => $page,
            'keywordEnglish' => $keywordEnglish,
        ]);
    }


    function category($categoryName, $categoryId, $page)
    {
        //$page = Input::get('page') == null ? 1 : Input::get('page');
        $sort = Input::get('sortOrder') == null ? "BestMatch" : Input::get('sortOrder');
        $checkedInput = $this->checkedChecked(Input::get());
        $breadCramb = $this->model->getBreadCramb($categoryId);

        $filterUrl = $this->generateFilterUrl(Input::get());
//        $ebayData = $this->model->getProductsByCategory(
//            $categoryId, 96, $page, Input::get(), $sort
//        );

        if (Cache::get('ebayCategory' . $categoryId . '-' . $page . '-' . $sort) == null) {
            Cache::put('ebayCategory' . $categoryId . '-' . $page . '-' . $sort, $this->model->getProductsByCategory(
                $categoryId, 96, $page, Input::get(), $sort), Config::get('cache.storeEbayIndex'));
        }
        $ebayData = Cache::get('ebayCategory' . $categoryId . '-' . $page . '-' . $sort);

        $similarCategories = $this->model->getSimilarCategory($categoryId);
        $filterData = $this->model->getFiltersForCategory($categoryId);
        $filtersForTitle = $this->model->getFiltersForTitle($checkedInput);
        $resultsAmount = $ebayData->totalEntries;
        if ($ebayData->totalPages > 100) {
            $totalPages = 100;
        } else {
            $totalPages = $ebayData->totalPages;
        }
        $pagesAvailable = Util::getPaginationList($page, $totalPages);
        $paginationResult = Util::getLinksForPagination($pagesAvailable, "category", $categoryName, $page, $this->siteslug, $filterUrl, $categoryId);

        $pages = $paginationResult['pages'];
        $pageNext = $paginationResult['pageNext'];

        $categoryData['page'] = $page;
        $categoryData['id'] = $categoryId;
        $categoryData['title'] = $categoryName;
        $categoryData['totalResults'] = $resultsAmount;
        if ($page == 1) {
            $pageForTitle = '';
        } else {
            $pageForTitle = ' ' . 'עמוד' . ' ' . $page;
        }
        /*if (is_null($sort)){
            $sortForTitle='';
        }else{
            $sortForTitle='-' . $sort;
        }*/
        if (Lang::getLocale() == 'he') {
            $this->shopName = Lang::get('general.ebay');
        } elseif (Lang::getLocale() == 'en') {
            $this->shopName = 'ebay';
        };
        //dd($filtersForTitle);
        $this->title =$categoryData['title']. ' - ' . $breadCramb['category'] . ' - ' . $filtersForTitle .  $categoryData['page'] . ' - '  . $this->shopName . ' - ' .'איביי' ;
        $this->description = $categoryData['title'] . ' ' . $filtersForTitle . $pageForTitle . '- ' . "איביי בעברית" . ' - ' . "שירות לקוחות בעברית,תשלום ללא כ.א בינלאומי,למעלה ממיליארד מוצרים בעברית";
        //dd($categoryData);
        $productBaseRoute = "/{$this->siteslug}/product";
        return view("ebay.category", [
            'pagination' => $pages,
            'pageNext' => $pageNext,
            'categoryData' => $categoryData,
            'categories' => $this->categories,
            'productBase' => $productBaseRoute,
            'siteslug' => $this->siteslug,
            'ebayData' => $ebayData->products,
            'filterData' => $filterData,
            'checkedInput' => $checkedInput,
            'breadCramb' => $breadCramb,
            'title' => $this->title,
            'description' => $this->description,
            'page' => $page,
            'similarCategories' => $similarCategories,
        ]);
    }

    function product($title, $id)
    {
        //$ebayData = $this->model->getProduct($id);
        if (Cache::get('ebayProduct' . $id) == null) {
            Cache::put('ebayProduct' . $id, $this->model->getProduct($id), Config::get('cache.storeEbayIndex'));
        }
        $ebayData = Cache::get('ebayProduct' . $id);
        //dd($ebayData);
        $breadCramb = $this->model->getBreadCramb(Input::get('categoryID'));

        //$similar_products = $this->model->getSimilarProduct($id, 20);

        if (Cache::get('ebaySimilarProduct' . $id) == null) {
            Cache::put('ebaySimilarProduct' . $id, $this->model->getSimilarProduct($id, 20),
                Config::get('cache.storeEbayIndex'));
        }
        $similar_products = Cache::get('ebaySimilarProduct' . $id);

        $feedbackData = $this->model->getFeedbackItem($id);
        if (isset($similar_products)) {
            foreach ($similar_products as $s_product) {
                foreach ($s_product->itemRecommendations->item as &$item) {
                    if (isset($item->buyItNowPrice->__value__) && $item->buyItNowPrice->__value__ != '0.00') {
                        $price = Util::replacePrices($item->buyItNowPrice->__value__);
                        $item->buyItNowPrice->__value__ = $price;
                    }
                    if (isset($item->currentPrice->__value__) && $item->currentPrice->__value__ != '0.00') {
                        $price = Util::replacePrices($item->currentPrice->__value__);
                        $item->currentPrice->__value__ = $price;
                    }
                }
            }
        }

        $productBaseRoute = "/{$this->siteslug}/product";
        $jsData = Util::includeJsWithData("jsData", $ebayData);
        $this->title = " איביי בעברית צ'יפי קניות ברשת" . $ebayData->Title;
        return view("ebay.product", [
            'exchangeRate' => $this->exchangeRate,
            'ebayData' => $ebayData,
            'shippingToIsrael' => $ebayData->shippingToIsrael,
            'jsData' => $jsData,
            'feedbackData' => $feedbackData,
            'categories' => $this->categories,
            'siteslug' => $this->siteslug,
            'breadCramb' => $breadCramb,
            'title' => $this->title,
            'productBase' => $productBaseRoute,
            'description' => $this->description,
            'similar_products' => $similar_products
        ]);
    }

    function oneDayDeals()
    {
        $ebayData = $this->model->getOneDayDeals();
        $isOneDayDeals = true;
        $productBaseRoute = "/{$this->siteslug}/product";
        $this->title = "איביי מבצעים בהנחות ענק";
        $this->description = "איביי מבצעים בהנחות ענק";

        return view("ebay.oneDayDeals", [
            'isOneDayDeals' => $isOneDayDeals,
            'ebayData' => $ebayData,
            'productBase' => $productBaseRoute,
            'categories' => $this->categories,
            'siteslug' => $this->siteslug,
            'title' => $this->title,
            'description' => $this->description,
        ]);
    }

    function categoryMap($categoryID)
    {
        $categories = $this->model->getCategory($categoryID);

        if (Lang::getLocale() == 'he') {
            $this->title = $categories[0]->catName;
        } elseif (Lang::getLocale() == 'en') {
            $this->title = $categories[0]->catEnglish;
        };
        return view("ebay.categoryMap", [
            'categoriesList' => $categories,
            'categories' => $this->categories,
            'siteslug' => $this->siteslug,
            'title' => $this->title,
            'description' => $this->description,
        ]);
    }

    function categoryList()
    {
        $categories = $this->model->getCategories();
        return view("ebay.categoryMap", [
            'categoriesList' => $categories,
            'categories' => $this->categories,
            'siteslug' => $this->siteslug,
            'title' => $this->title,
            'description' => $this->description,
        ]);
    }
}
