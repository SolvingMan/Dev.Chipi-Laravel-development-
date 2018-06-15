<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 24.08.2017
 * Time: 15:15
 */

namespace App\Http\Controllers;


use App\Models\Asos\AsosModel;
use App\Models\Next\NextModel;
use App\Util;
use Illuminate\Support\Facades\Input;
use App\Market;
use Lang;

class AsosController extends Controller
{
    private $asosModel;
    private $siteslug;
    private $categories;
    private $title;
    private $description;
    private $nextModel;
    private $market;
    private $shopName;

    //private $market;
    function __construct()
    {
        parent::__construct();
        //$this->market = new Market();
        $this->asosModel = new AsosModel();
        $this->nextModel = new NextModel();
        $this->market = new Market();
        $this->siteslug = "asos";
        $this->categories = $this->asosModel->getCategories();
        $this->title = " נקסט בעברית , הפלטפורמה המובילה להזמנה מ ebay בעברית - צ'יפי קניות ברשת";
        $this->description = "נקסט בעברית , עכשיו זה אפשרי לרכוש בראש שקט,שירות לקוחות בעברית,תשלום ללא כ.א בינלאומי,למעלה ממיליארד מוצרים.";
    }

    function product($title, $productId)
    {
        $categoryId = Input::get("cid");
        $breadCramb = $this->asosModel->getBreadCramb($categoryId);
        $productData = $this->asosModel->getProduct($title, $productId);
        $jsProductData = Util::includeJsWithData("jsProductData", $productData);
        $productBaseRoute = "/{$this->siteslug}/product";
        return view("asos.product", [
            'breadCramb' => $breadCramb,
            'categoryId' => $categoryId,
            'productBase' => $productBaseRoute,
            'productData' => $productData,
            'siteslug' => $this->siteslug,
            'categories' => $this->categories,
            'jsProductData' => $jsProductData
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
        if ($keyword) {
            if (Util::is_hebrew($keyword)) {
                $keywordEnglish = Util::translateHebrewToEnglishFromDatabase($keyword);
            } else {
                $keywordEnglish = $keyword;
            }

            $deletedWords = array(" for ", " in ", " it ", " a ", " the ", " of ", " or ", " I ", " you ", " he ", " me ", " us ", " they ",
                " she ", " to ", " but ", " that ", " this ", " those ", " then ", " with ");
            $parameters = Input::get();
            $keywordEnglish = $this->deleteWordsFromString($deletedWords, $keywordEnglish);
            $filterUrl = $this->generateFilterUrl($parameters);
            $sort = Input::get('sortOrder') == null ? "" : Input::get('sortOrder');
            $asosData = $this->asosModel->getProductsByKeywords(
                $keywordEnglish, $page, $sort
            );
        }

        if ($asosData['products'] != null) {
            $totalPages = $asosData['totalPages'];
            $totalEntries = $asosData['totalEntries'];
            $pagesAvailable = Util::getPaginationList($page, $totalPages);
            $paginationResult = Util::getLinksForPagination($pagesAvailable, "search", $keyword, $page, $this->siteslug,
                $filterUrl);
            $pages = $paginationResult['pages'];
            $pageNext = $paginationResult['pageNext'];
        } else {
            $pages = null;
            $pageNext = null;
            $totalEntries = null;
        }

        $productBaseRoute = "/{$this->siteslug}/product";

        $this->title = "צ'יפי קניות ברשת  איביי בעברית " . $keyword . "חפש ";

        return view("asos.search", [
            'pagination' => $pages,
            'pageNext' => $pageNext,
            'categories' => $this->categories,
            'productBase' => $productBaseRoute,
            'siteslug' => $this->siteslug,
            'asosData' => $asosData['products'],
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
        $sort = Input::get('sortOrder') == null ? "" : Input::get('sortOrder');
        $parameters = Input::get();
        //$itemPerPage = 96;

        $breadCramb = $this->asosModel->getBreadCramb($categoryId);

        $filterUrl = $this->generateFilterUrl($parameters);
        $asosData = $this->asosModel->getProductsByCategory($categoryId, $page, $sort);

        $totalProductsCount = $asosData['totalEntries'];

        $totalPages = $asosData['totalPages'];

        $pagesAvailable = Util::getPaginationList($page, $totalPages);
        $paginationResult = Util::getLinksForPagination($pagesAvailable, "category", $categoryName, $page, $this->siteslug,
            $filterUrl, $categoryId);

        $pages = $paginationResult['pages'];
        $pageNext = $paginationResult['pageNext'];

        $categoryData['page'] = $page;
        $categoryData['id'] = $categoryId;
        $categoryData['title'] = $categoryName;
        $categoryData['totalResults'] = $totalProductsCount;

      /* if (is_null($sort)){
        $sortForTitle='';
    }else{
$sortForTitle='-' . $sort;
}*/
//dd($filtersForTitle);
        if (Lang::getLocale() == 'he') {
            $this->shopName = Lang::get('general.asos');
        } elseif (Lang::getLocale() == 'en') {
            $this->shopName = 'asos';
        };
$this->title =$categoryData['title']. ' - ' . $breadCramb['category'] . $categoryData['page'] . ' - '  . $this->shopName . ' - ' . 'אסוס' ;
        $productBaseRoute = "/{$this->siteslug}/product";
        return view("asos.category", [
            'pagination' => $pages,
            'pageNext' => $pageNext,
            'categoryData' => $categoryData,
            'categories' => $this->categories,
            'productBase' => $productBaseRoute,
            'siteslug' => $this->siteslug,
            'asosData' => $asosData['products'],
            'title' => $this->title,
            'description' => $this->description,
            'page' => $page,
            'breadCramb' => $breadCramb,
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

    function categoryMap($categoryID)
    {
        $categories = $this->asosModel->getCategory($categoryID);
        return view("asos.categoryMap", [
            'categoriesList' => $categories,
            'categories' => $this->categories,
            'siteslug' => $this->siteslug,
            'title' => $this->title,
            'description' => $this->description,
        ]);
    }

    function categoryList()
    {
        $categories = $this->asosModel->getCategories();
        return view("asos.categoryMap", [
            'categoriesList' => $categories,
            'categories' => $this->categories,
            'siteslug' => $this->siteslug,
            'title' => $this->title,
            'description' => $this->description,
        ]);
    }
    public function index()
    {
        if (\Cache::get('nextMenFashion') == null) {
            \Cache::put('nextMenFashion', $this->nextModel->getProductsByCategory(138, 24, 1), \Config::get('cache.storeEbayIndex'));
        }
        if (\Cache::get('nextWomenFashion') == null) {
            \Cache::put('nextWomenFashion', $this->nextModel->getProductsByCategory(99, 24, 1), \Config::get('cache.storeEbayIndex'));
        }
        if (\Cache::get('babyClothes') == null) {
            \Cache::put('babyClothes', $this->nextModel->getProductsByCategory(95, 24, 1), \Config::get('cache.storeEbayIndex'));
        }

        $menFashion = \Cache::get('nextMenFashion');
        $womenFashion = \Cache::get('nextWomenFashion'); //Cache::get('ebayWomenFashion');
        $babyClothes = \Cache::get('babyClothes');


        //$latestDeals = $this->nextModel->getLatestDeals($menFashion, $womenFashion, $babyClothes);

        $recommendItems = $this->market->getRecommendProducts(\DB::table('recommendedItems')
            ->where('startDeal', '=', date('Y-m-d'))->get());

        $productBaseRoute = "/{$this->siteslug}/product";
        return view("asos.index", [
           // 'menFashion' => $menFashion,
            'womenFashion' => $womenFashion,
            'babyClothes' => $babyClothes,
            //'latestDeals' => $latestDeals,
            'categories' => $this->categories,
            'siteslug' => $this->siteslug,
            'title' => $this->title,
            'description' => $this->description,
            'productBase' => $productBaseRoute,
            'recommendItems' => $recommendItems,
        ]);
    }
}
