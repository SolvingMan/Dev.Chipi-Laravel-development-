<?php
namespace App\Http\Controllers;


use App\Market;
use App\Models\Ebay\EbayCategories;
use App\Models\Next\NextModel;
use App\Util;
use Illuminate\Support\Facades\Input;
use Lang;

class NextController extends Controller
{
    private $nextModel;
    private $siteslug;
    private $categories;
    private $title;
    private $description;
    private $market;
    private $currentTab;
    private $timerTime;
    private $shopName;

    function __construct()
    {
        parent::__construct();
        $this->market = new Market();
        $this->nextModel = new NextModel();
        $this->siteslug = "next";
        $data = Util::chooseCurrentTabAndTimer();
        $this->currentTab = $data["tab"];
        $this->timerTime = $data["timerTime"];
        $this->categories = $this->nextModel->getCategories();
        $this->title = " נקסט בעברית , הפלטפורמה המובילה להזמנה מ נקסט בעברית - צ'יפי קניות ברשת";
        $this->description = "נקסט בעברית , עכשיו זה אפשרי לרכוש בראש שקט,שירות לקוחות בעברית,תשלום ללא כ.א בינלאומי,למעלה ממיליארד מוצרים.";
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


        $latestDeals = $this->nextModel->getLatestDeals($menFashion, $womenFashion, $babyClothes);

        $recommendItems = $this->market->getRecommendProducts(\DB::table('recommendedItems')
            ->where('startDeal', '=', date('Y-m-d'))->get());

        $this->title = "נקסט בעברית , הפלטפורמה המובילה להזמנה מ next בעברית - צ&#039;יפי קניות ברשת";
        $productBaseRoute = "/{$this->siteslug}/product";
        return view("next.index", [
            'timerTime' => $this->timerTime,
            'menFashion' => $menFashion,
            'womenFashion' => $womenFashion,
            'babyClothes' => $babyClothes,
            'latestDeals' => $latestDeals,
            'categories' => $this->categories,
            'siteslug' => $this->siteslug,
            'title' => $this->title,
            'description' => $this->description,
            'productBase' => $productBaseRoute,
            'recommendItems' => $recommendItems,
        ]);
    }

    function search($keyword, $page)
    {
        $itemPerPage = 24;
        if ($keyword) {
            if (Util::is_hebrew($keyword)) {
                $keywordEnglish = Util::translateHebrewToEnglishFromDatabase($keyword);
                Util::storeSearchRequest($keywordEnglish, "next", $keyword);
            } else {
                $keywordEnglish = $keyword;
                Util::storeSearchRequest($keywordEnglish, "next", $keyword);
            }
            $deletedWords = array(" for ", " in ", " it ", " a ", " the ", " of ", " or ", " I ", " you ", " he ", " me ", " us ", " they ",
                " she ", " to ", " but ", " that ", " this ", " those ", " then ", " with ");
            $keywordEnglish = $this->deleteWordsFromString($deletedWords, $keywordEnglish);

            $nextModelData = $this->nextModel->getProductsByKeyword($page, $keywordEnglish, $itemPerPage);
        }
        //dd($nextModelData);
        if ($nextModelData != null) {
            $totalProductsCount = $nextModelData['countProducts'];
            $totalPages = $nextModelData['countPages'];
            $products = $nextModelData['products'];
            $pagesAvailable = Util::getPaginationList($page, $totalPages);
            $paginationResult = Util::getLinksForPagination($pagesAvailable, "search", $keyword, $page, $this->siteslug);
            $pages = $paginationResult['pages'];
            $pageNext = $paginationResult['pageNext'];
        } else {
            $pages = null;
            $pageNext = null;
            $totalEntries = null;
        }
        $this->title = "צ'יפי קניות ברשת  נקסט בעברית " . $keyword . "חפש ";
        $productBaseRoute = "/{$this->siteslug}/product";
        return view("next.search", [
            'timerTime' => $this->timerTime,
            'pagination' => $pages,
            'pageNext' => $pageNext,
            'categories' => $this->categories,
            'productBase' => $productBaseRoute,
            'siteslug' => $this->siteslug,
            'nextData' => $products,
            'title' => $this->title,
            'totalEntries' => $totalProductsCount,
            'description' => $this->description,
            'keyword' => $keyword,
            'page' => $page,
            'keywordEnglish' => $keywordEnglish,
        ]);
    }

    function category($categoryName, $categoryId, $page)
    {
        $sort = Input::get('sortOrder') == null ? "id|asc" : Input::get('sortOrder');
        $itemPerPage = 96;
        $parameters = Input::get();
        $filterUrl = $this->generateFilterUrl($parameters);
        $breadCramb = $this->nextModel->getBreadCramb($categoryId);

        $nextData = $this->nextModel->getProductsByCategory($categoryId, $itemPerPage, $page, $sort);

        $totalProductsCount = $this->nextModel->getCountProducts($categoryId);

        $totalPages = ceil($totalProductsCount / $itemPerPage);

        $pagesAvailable = Util::getPaginationList($page, $totalPages);
        $paginationResult = Util::getLinksForPagination($pagesAvailable, "category", $categoryName, $page, $this->siteslug, $filterUrl, $categoryId);

        $pages = $paginationResult['pages'];
        $pageNext = $paginationResult['pageNext'];

        $categoryData['page'] = $page;
        $categoryData['id'] = $categoryId;
        $categoryData['title'] = $categoryName;
        $categoryData['totalResults'] = $totalProductsCount;

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
        //dd($filtersForTitle);
        if (Lang::getLocale() == 'he') {
            $this->shopName = Lang::get('general.next');
        } elseif (Lang::getLocale() == 'en') {
            $this->shopName = 'next';
        };
        $this->title =$categoryData['title']. ' - ' . $breadCramb['category'] . ' - ' . $categoryData['page']   . $this->shopName . ' - ' . 'נקסט' ;
        $this->description = $categoryData['title'] . $pageForTitle . '- ' . "נקסט בעברית" . ' - ' ."שירות לקוחות בעברית,תשלום ללא כ.א בינלאומי,למעלה ממיליארד מוצרים בעברית";
        $productBaseRoute = "/{$this->siteslug}/product";

        return view("next.category", [
            'timerTime' => $this->timerTime,
            'pagination' => $pages,
            'pageNext' => $pageNext,
            'categoryData' => $categoryData,
            'categories' => $this->categories,
            'productBase' => $productBaseRoute,
            'siteslug' => $this->siteslug,
            'nextData' => $nextData,
            'title' => $this->title,
            'description' => $this->description,
            'page' => $page,
            'breadCramb' => $breadCramb,
        ]);
    }

    function deleteWordsFromString($deletedWords, $string)
    {
        foreach ($deletedWords as $word) {
            $string = str_replace($word, "", $string);
        }
        return $string;
    }

    function product($title, $productId)
    {
        if ($productId == 0) {
            $title = str_replace('@', '#', $title);
            $productUrl = 'http://www.next.co.il/he/' . $title;
        } else {
            $productUrl = $this->nextModel->getProductUrl($productId);
        }
        $categoryID = $this->nextModel->getCategoryIdByProduct($productUrl);
        $breadCramb = $this->nextModel->getBreadCramb($categoryID);
        $productData = $this->nextModel->getProduct($productUrl);
        $fits = array();
        $haveColor = true;
        foreach ($productData['additionalImagesAndVarilables']['Styles']['Fits'] as $item) {
            if ($item['Name'] != null) {
                array_push($fits, $item['Name']);
            } else {
                $fits = null;
            }
            foreach ($item['Items'] as $color) {
                if ($color['Colour'] == null) {
                    $haveColor = false;
                }
            }
        }
        $similarProducts = $this->nextModel->getSimilarProducts($productUrl);
        $imageUrl = Util::includeJsWithData('imageUrl', $productData['getMainImages']);
        $productUrl = Util::includeJsWithData('productUrl', $productUrl);
        $countFits = count($productData['additionalImagesAndVarilables']['Styles']['Fits']);
        $productBaseRoute = "/{$this->siteslug}/product";
        return view("next.product", [
            'timerTime' => $this->timerTime,
            'productBase' => $productBaseRoute,
            'productData' => $productData,
            'imageUrl' => $imageUrl,
            'productUrl' => $productUrl,
            'fits' => $fits,
            'haveColor' => $haveColor,
            'countFits' => $countFits,
            'siteslug' => $this->siteslug,
            'categories' => $this->categories,
            'similarProducts' => $similarProducts,
            'breadCramb' => $breadCramb,
            'categoryID' => $categoryID
        ]);
    }

    function categoryMap($categoryID)
    {
        $categories = $this->nextModel->getCategory($categoryID);
        if (Lang::getLocale() == 'he') {
            $this->title = $categories[0]->catName;
        } elseif (Lang::getLocale() == 'en') {
            $this->title = $categories[0]->catEnglish;
        };

        return view("next.categoryMap", [
            'timerTime' => $this->timerTime,
            'categoriesList' => $categories,
            'categories' => $this->categories,
            'siteslug' => $this->siteslug,
            'title' => $this->title,
            'description' => $this->description,
        ]);
    }

    function categoryList()
    {
        return view("next.categoryMap", [
            'timerTime' => $this->timerTime,
            'categoriesList' => $this->categories,
            'categories' => $this->categories,
            'siteslug' => $this->siteslug,
            'title' => $this->title,
            'description' => $this->description,
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
}