<?php

namespace App\Http\Controllers;

use App\Market;
use Illuminate\Http\Request;
use App\Util;
use Config;
use Cache;
use App\Models\Ebay\EbayCategories;
use Illuminate\Support\Facades\Input;
use Lang;

class AmazonController extends Controller
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
        $this->siteslug = "amazon";
        $this->title = " אמזון בעברית , הפלטפורמה המובילה לרכישה מAmazon בעברית - צ'יפי קניות ברשת";
        $this->description = "רמזון בעברית , עכשיו זה אפשרי לרכוש בראש שקט,שירות לקוחות בעברית,תשלום ללא כ.א בינלאומי,למעלה ממיליארד מוצרים.";
        $this->middleware(function ($request, $next) {

            // change those to fetchable values (some time later)
//            $baseCurrency = "USD";
//            $endCurrency = "ILS";
//            $this->exchangeRate = Util::getExchangeRate($baseCurrency, $endCurrency);
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
        $womenFashion = Cache::get('ebayWomenFashion'); //Cache::get('ebayWomenFashion');
        $electronic = Cache::get('ebayElectronic');

        $menFashion = $menFashion->products;
        $womenFashion = $womenFashion->products;
        $electronic = $electronic->products;

        //dd($womenFashion);
//        if (Cache::get('ebayLatestDeals') == null) {
//            Cache::put('ebayLatestDeals', $this->market->getLatestDeals($menFashion, $womenFashion, $electronic), Config::get('cache.storeEbayIndex'));
//        }

        //  $latestDeals = Cache::get('ebayLatestDeals');


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
        //dd($getParameters);
        unset($getParameters["page"]);
        $result = "";
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
//        dd($description);
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
        //$searchKeywords = $keyword;

        if ($keyword) {
            //$searchKeyword = $searchKeywords;
            if (Util::is_hebrew($keyword)) {
                $keywordEnglish = Util::translateHebrewToEnglishFromDatabase($keyword);
                Util::storeSearchRequest($keywordEnglish, "amazon", $keyword);
            } else {
                $keywordEnglish = $keyword;
                Util::storeSearchRequest($keywordEnglish, "amazon", $keyword);
            }

            $deletedWords = array(" for ", " in ", " it ", " a ", " the ", " of ", " or ", " I ", " you ", " he ", " me ", " us ", " they ",
                " she ", " to ", " but ", " that ", " this ", " those ", " then ", " with ");

            $keywordEnglish = $this->deleteWordsFromString($deletedWords, $keywordEnglish);

            $ebayData = $this->model->getProductsByKeywords(
                $keywordEnglish, 96, $page, $parameters, $sort
            );
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
        $ebayData = $this->model->getProductsByCategory(
            $categoryId, 42, $page, Input::get(), $sort
        );
        $similarCategories = $this->model->getSimilarCategory($categoryId);
        $filterData = $this->model->getFiltersForCategory($categoryId);

        $resultsAmount = $ebayData->totalEntries;
        if ($ebayData->totalPages > 100) {
            $totalPages = 100;
        } else {
            $totalPages = $ebayData->totalPages;
        }
        $pagesAvailable = Util::getPaginationList($page, $totalPages);
        $paginationResult = Util::getLinksForPagination($pagesAvailable, "category", $categoryName, $page, $this->siteslug, $filterUrl, $categoryId);
        $filtersForTitle = $this->model->getFiltersForTitle($checkedInput);
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
//        $this->title = $categoryData['title'] . ' ' . $filtersForTitle . $pageForTitle . '- ' . "אמזון בעברית" . ' - ' . 'צי\'פי קניות ברשת';
        /*if (is_null($sort)) {
            $sortForTitle = '';
        } else {
            $sortForTitle = '-' . $sort;
        }*/
        if (Lang::getLocale() == 'he') {
            $this->shopName = Lang::get('general.amazon');
        } elseif (Lang::getLocale() == 'en') {
            $this->shopName = 'amazon';
        };
        //dd($filtersForTitle);
        $this->title = $categoryData['title'] . ' - ' . $breadCramb['category'] . $filtersForTitle . ' - ' . $categoryData['page'] . ' - ' . $this->shopName;
        $this->description = $categoryData['title'] . ' ' . $filtersForTitle . $pageForTitle . '- ' . "אמזון בעברית" . ' - ' . "שירות לקוחות בעברית,תשלום ללא כ.א בינלאומי,למעלה ממיליארד מוצרים בעברית";
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
        $ebayData = $this->model->getProduct($id);
        $breadCramb = $this->model->getBreadCramb(Input::get('categoryID'));
        $jsData = Util::includeJsWithData("jsData", $ebayData);
        $this->title = " אמזון בעברית צ'יפי קניות ברשת" . $ebayData->Title;
//        dd($ebayData);
        //$feedbackData = $ebayData->getFeedback($id);
        return view("ebay.product", [
            'exchangeRate' => $this->exchangeRate,
            'ebayData' => $ebayData,
            'shippingToIsrael' => $ebayData->shippingToIsrael,
            'jsData' => $jsData,
            //'feedbackData' => $feedbackData,
            'categories' => $this->categories,
            'siteslug' => $this->siteslug,
            'breadCramb' => $breadCramb,
            'title' => $this->title,
            'description' => $this->description,
        ]);
    }

    function oneDayDeals()
    {
        $ebayData = $this->model->getOneDayDeals();
        $productBaseRoute = "/{$this->siteslug}/product";
        return view("ebay.oneDayDeals", [
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
