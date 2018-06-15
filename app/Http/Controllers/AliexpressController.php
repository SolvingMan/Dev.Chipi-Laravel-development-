<?php

namespace App\Http\Controllers;

use App;
use App\Http\API\AliexpressAPI;
use App\Models\Aliexpress\Aliexpress;
use App\Util;
use Cache;
use Config;
use FOF30\Form\Header\Date;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Rules\In;
use Lang;
use Symfony\Component\HttpFoundation\Request;

class AliexpressController extends Controller
{
    private $categories;
    private $siteslug;
    private $model;
    private $lang;
    private $title;
    private $description;
    private $market;
    private $currentTab;
    private $timerTime;
    private $shopName;

    function __construct()
    {
        parent::__construct();
        // this way we can access session in constructor (Laravel 5.3+ doesn't provide that functionality)
        $this->middleware(function ($request, $next) {

            $this->model = new Aliexpress();
            $this->lang = App::getLocale();
            $this->title = Lang::get('general.aliexpress_general_title');

            return $next($request);
        });

        $this->market = new App\Market();
        $this->siteslug = "aliexpress";
        $this->categories = Aliexpress::getCategories();


        $data = Util::chooseCurrentTabAndTimer();
        $this->currentTab = $data["tab"];
        $this->timerTime = $data["timerTime"];


        $this->description = "עליאקספרס בעברית , עכשיו זה אפשרי לרכוש בראש שקט,שירות לקוחות בעברית,תשלום ללא כ.א בינלאומי,למעלה ממיליארד מוצרים.";
    }


    function index()
    {
        $cats = [
            'aliMenFashion' => 200002136,
            'aliWomenFashion' => 200003482,
            'aliElectronic' => 5090301,
        ];
        $aliexpressApi = new AliexpressAPI();
        foreach ($cats as $key => $val) {
            if (Cache::get($key) == null) {
                Cache::put($key, $this->model->parseCategoryData(json_decode(
                    $aliexpressApi->getCategoryProducts($val, 39, "USD", App::getLocale()))),
                    Config::get('cache.storeAliHotProducts'));
            }
        }
        $recommendItems = $this->market->getRecommendProducts(\DB::table('recommendedItems')
            ->where('startDeal', '=', date('Y-m-d'))->get());
        $menFashion = Cache::get('aliMenFashion');
        $womenFashion = Cache::get('aliWomenFashion');
        $electronic = Cache::get('aliElectronic');

        if (Cache::get('aliLatestDeals') == null) {
            Cache::put('aliLatestDeals', $this->market->getLatestDeals($menFashion, $womenFashion, $electronic, 'ali'), Config::get('cache.storeAliHotProducts'));
        }

        $latestDeals = Cache::get('aliLatestDeals');

        // starting part for product url
        $productBaseRoute = "/{$this->siteslug}/product";

        return view("aliexpress.index", [
            'timerTime' => $this->timerTime,
            'productBase' => $productBaseRoute,
            'menFashion' => $menFashion,
            'womenFashion' => $womenFashion,
            'electronic' => $electronic,
            'latestDeals' => $latestDeals,
            'categories' => $this->categories,
            'siteslug' => $this->siteslug,
            'title' => $this->title,
            'description' => $this->description,
            'cats' => $cats,
            'recommendItems' => $recommendItems,
        ]);
    }

    function search($keyword, $page)
    {
        $searchString = $keyword;
        $sort = Input::get('sort');
        $id = -1;
        $itemsPerPage = 40;

        if ($searchString) {
            if (Util::is_hebrew($searchString)) {
                $keywordEnglish = Util::translateHebrewToEnglishFromDatabase($searchString);
                Util::storeSearchRequest($keywordEnglish, "aliexpress", $searchString);
            } else {
                $keywordEnglish = $searchString;
                Util::storeSearchRequest($keywordEnglish, "aliexpress");
            }
        }
        $aliexpressApi = new AliexpressAPI();

        $options = [
            "keywords" => str_replace(" ", ",", $keywordEnglish),
            "sort" => $sort,
            'pageNo' => $page
        ];

        if (Cache::get('aliSearch' . $keywordEnglish . '-' . $page . '-' . $sort) == null) {
            Cache::put('aliSearch' . $keywordEnglish . '-' . $page . '-' . $sort, json_decode($aliexpressApi->getCategoryProducts(
                $id, $itemsPerPage, "USD", App::getLocale(), $options)), Config::get('cache.storeAliHotProducts'));
        }
        $aliData = Cache::get('aliSearch' . $keywordEnglish . '-' . $page . '-' . $sort);

        if (!isset($aliData->result)) {
            return redirect()->back();
        } else {
            $resultsAmount = $aliData->result->totalResults;
        }

        // pagination ( not the perfect of implementations, but it works )
        $totalPages = ceil($aliData->result->totalResults / $itemsPerPage);
        $totalPages = $totalPages > 100 ? 100 : $totalPages;
        $pagesAvailable = Util::getPaginationList($page, $totalPages);

        $categoryData['page'] = $page;
        $categoryData['id'] = $id;
        $categoryData['title'] = $searchString;
        $categoryData['sort'] = $options['sort'];

        // product forms action route base
        $productBaseRoute = "/{$this->siteslug}/product";

        $paginationResult = Util::getLinksForPagination($pagesAvailable, "search", $keyword, $page, $this->siteslug);
        $pages = $paginationResult['pages'];
        $pageNext = $paginationResult['pageNext'];
        $this->title = "צ'יפי קניות ברשת  עליאקספרס בעברית " . $searchString . "חפש ";
        return view("aliexpress.category", [
            'timerTime' => $this->timerTime,
            'totalResults' => $aliData->result->totalResults,
            'nextPageLink' => $pageNext,
            'pageLinks' => $pages,
            'pagination' => $pagesAvailable,
            'categoryData' => $categoryData,
            'productBase' => $productBaseRoute,
            'aliData' => $this->model->parseCategoryData($aliData),
            'categories' => $this->categories,
            'siteslug' => $this->siteslug,
            'title' => $this->title,
            'description' => $this->description,
            'keyword' => $keyword,
            'page' => $page,
        ]);
    }

    function category($title, $id, $page)
    {
        //$page = Input::get('page', 1);
        $sort = Input::get('sort');

        $aliexpressApi = new AliexpressAPI();

        $options = [
            "sort" => $sort,
            "pageNo" => $page
        ];
        if (Cache::get('aliCategory' . $id . '-' . $page . '-' . $sort) == null) {
            Cache::put('aliCategory' . $id . '-' . $page . '-' . $sort, json_decode($aliexpressApi->getCategoryProducts($id,
                39, "USD", App::getLocale(), $options)), Config::get('cache.storeAliHotProducts'));
        }
        $aliData = Cache::get('aliCategory' . $id . '-' . $page . '-' . $sort);

        $similarCategories = $this->model->getSimilarCategory($id);
        $breadCramb = $this->model->getBreadCramb($id);
        $resultsAmount = isset($aliData->result->totalResults) ? $aliData->result->totalResults : 100;

        // pagination ( not the perfect of implementations, but it works )
        $totalPages = 100;
        $pagesAvailable = Util::getPaginationList($page, $totalPages);

        $categoryData = $this->makeCategoryData($page, $id, $title, $sort, $resultsAmount);

        // product forms action route base
        $productBaseRoute = "/{$this->siteslug}/product";
        $categoryBaseRoute = "/{$this->siteslug}/category/$title/$id";

        $paginationResult = Util::getLinksForPagination($pagesAvailable, "category", $title, $page, $this->siteslug, null, $id);

        $pages = $paginationResult['pages'];
        $pageNext = $paginationResult['pageNext'];

        if ($page == 1) {
            $pageForTitle = '';
        } else {
            $pageForTitle = ' ' . 'עמוד' . ' ' . $page;
        }
        /*if (is_null($categoryData['sort'])){
            $sortForTitle='';
        }else{
            $sortForTitle='-' . $categoryData['sort'];
        }*/
       // dd($categoryData);
        if (Lang::getLocale() == 'he') {
            $this->shopName = Lang::get('general.aliexpress');
        } elseif (Lang::getLocale() == 'en') {
            $this->shopName = 'aliexpress';
        };
        $this->title =$categoryData['title']. ' - ' . $breadCramb['category']. ' - ' . $this->shopName . ' - ' . $categoryData['page'] . ' - ' .'עליאקספרס'  ;
//        $this->title = $categoryData['title'];
        $this->description = $categoryData['title'] . $pageForTitle . '- ' . "עליאקספרס בעברית" . ' - ' . "שירות לקוחות בעברית,תשלום ללא כ.א בינלאומי,למעלה ממיליארד מוצרים בעברית";
        return view("aliexpress.category", [
            'timerTime' => $this->timerTime,
            'nextPageLink' => $pageNext,
            'pageLinks' => $pages,
            'pagination' => $pagesAvailable,
            'categoryData' => $categoryData,
            'productBase' => $productBaseRoute,
            'categoryBase' => $categoryBaseRoute,
            'aliData' => $this->model->parseCategoryData($aliData),
            'categories' => $this->categories,
            'siteslug' => $this->siteslug,
            'breadCramb' => $breadCramb,
            'title' => $this->title,
            'description' => $this->description,
            'page' => $page,
            'similarCategories' => $similarCategories,
        ]);
    }

    private function makeCategoryData($page, $id, $title, $sort, $resultsAmount)
    {
        $categoryData = [];
        $categoryData['page'] = $page;
        $categoryData['id'] = $id;
        $categoryData['title'] = $title;
        $categoryData['sort'] = $sort;
        $categoryData['totalResults'] = $resultsAmount;

        return $categoryData;
    }

    function description($title, $id, $language)
    {
        $aliURL = "{$this->model->productURLBase}{$title}/{$id}.html";
        $aliexpressApi = new AliexpressAPI($aliURL);
        $description = $aliexpressApi->getAdditionalImages();
        return view("aliexpress.description", [
            'language' => $language,
            'description' => $description,
        ]);
    }

    function product($title, $id)
    {
        // conjure real aliexpress link from url of product here
        $aliURL = "{$this->model->productURLBase}{$title}/{$id}.html";
        $productBaseRoute = "/{$this->siteslug}/product";
        $aliexpressApi = new AliexpressAPI($aliURL);
        $similar_products = $this->model->getSimilarProduct($id);
        $similar_products_ali = json_decode($similar_products);
        $cache = true;
        $getImages = true;
        if ($cache) {
            if (Cache::get("product_$id") == null) {
                Cache::put("product_$id",
                    $aliexpressApi->getTotalProductData($getImages),
                    Config::get("cache.storeAliSingleProduct")
                );
            }
            $aliData = Cache::get("product_$id");
        } else {
            $aliData = $aliexpressApi->getTotalProductData($getImages);
        }

        $aliData = $this->model->parseProductData($aliData);

        $feedback_link = Util::checkHTTPS($aliData['feedback']);

        $stars = $aliData['percentNum'] / 5 * 100;

        $breadCramb = $this->model->getBreadCramb(Input::get('categoryID'));
//        dd($breadCramb);
        $jsData = Util::includeJsWithData("ProductData", [
            'totalSkuNum' => count($aliData['productSKU']),
            'priceRange' => $aliData['mainPrice'],
            'skuVariations' => $aliData['productSkuJSON'],
            'skuData' => $aliData['skuArrayJSON'],
            'image' => $aliData['mainImages'][0],
            'shipping' => $aliData['shipping']
        ]);


        $this->title = "עליאקספרס בעברית צ'יפי קניות ברשת" . $aliData['productName'];
        if (isset($similar_products_ali->result)) {
            foreach ($similar_products_ali->result->products as $product) {
                $price = Util::replacePrices($product->salePrice);
                $product->salePrice = $price;
                $url_html = $productBaseRoute . "/" . explode("/", $product->productUrl)[4] . "/" . explode("?", explode("/", $product->productUrl)[5])[0];
                $new_url = str_replace('.html', "", $url_html);
                $product->productUrl = $new_url;
            }
        }

        return view("aliexpress.product", [
            'timerTime' => $this->timerTime,
            'stars' => $stars,
            'productData' => $jsData,
            'aliData' => $aliData,
            'categories' => $this->categories,
            'siteslug' => $this->siteslug,
            'breadCramb' => $breadCramb,
            'productID' => $id,
            'title' => $title,
            'title' => $this->title,
            'description' => $this->description,
            'similar_products' => $similar_products_ali,
            'productBase' => $productBaseRoute,
            'feedback_link' => $feedback_link
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
        return view("aliexpress.categoryMap", [
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
        $categories = $this->model->getCategoriesList();

        return view("aliexpress.categoryMap", [
            'timerTime' => $this->timerTime,
            'categoriesList' => $categories,
            'categories' => $this->categories,
            'siteslug' => $this->siteslug,
            'title' => $this->title,
            'description' => $this->description,
        ]);
    }

    function oneDayDeals()
    {
        if (Cache::get('aliDealsWidgetPhase') == null) {
            Cache::put('aliDealsWidgetPhase', $this->model->getWidgetPhaseArray(), Config::get('cache.storeAliDeals'));
        }
        $widgetPhaseArray = Cache::get('aliDealsWidgetPhase');

        $widgetArray = $widgetPhaseArray['widgetArray'];
        $phaseArray = $widgetPhaseArray['phaseArray'];
        if (Cache::get('aliDealsProducts') == null) {
            Cache::put('aliDealsProducts', $this->model->getAliDeals(12, 0, $widgetArray[$this->currentTab - 1],
                $phaseArray[$this->currentTab - 1]),
                Config::get('cache.storeAliDeals'));
        }
        $aliData = Cache::get('aliDealsProducts');
        $isOneDayDeals = true;
        $productBaseRoute = "/{$this->siteslug}/product";
        $aliData = $this->model->parseDealsData($aliData);
        $endTime = $this->model->minTime($aliData);
        $this->title = "עליאקספרס בעברית עושה הנחות ענק";
        $this->description = "עליאקספרס בעברית עושה הנחות ענק";

        return view("aliexpress.oneDayDeals", [
            'timerTime' => $this->timerTime,
            'currentTab' => $this->currentTab,
            'isOneDayDeals' => $isOneDayDeals,
            'aliData' => $aliData->gpsProductDetails,
            'productBase' => $productBaseRoute,
            'categories' => $this->categories,
            'siteslug' => $this->siteslug,
            'title' => $this->title,
            'description' => $this->description,
            'endTime' => $endTime,
            'widgetArray' => $widgetArray,
            'phaseArray' => $phaseArray,
        ]);
    }
}
