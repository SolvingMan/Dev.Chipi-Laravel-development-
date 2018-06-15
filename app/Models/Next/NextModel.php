<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 26.07.2017
 * Time: 15:25
 */

namespace App\Models\Next;

use App\Http\API\NextAPI;
use App\Util;
use Illuminate\Database\Eloquent\Model;

class NextModel extends Model
{
    protected $table = "nextCats";
    protected $subTable = "nextSubcats";
    protected $subSubTable = "nextSubSubcats";
    private $nextAPI;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->nextAPI = new NextAPI();
    }

    public function getCountProducts($categoryID)
    {
        return \DB::table('nextProductsByCategory')->where('categoryID', '=', $categoryID)->count();
    }

    public function getProductsByCategory($categoryID, $itemPerPage, $page, $sort = 'id|asc')
    {
        $sort = explode('|', $sort);
        $skip = $itemPerPage * ($page - 1);
        $products = \DB::table('nextProductsByCategory')->where('categoryID', '=', $categoryID)
            ->orderBy($sort[0], $sort[1])->skip($skip)->take($itemPerPage)->get();

        for ($i = 0; $i < count($products); $i++) {
            $price = explode('-', $products[$i]->price);
            $products[$i]->price = Util::replacePrices($price, "ILS", "next");
        }
        return $products;
    }

    public function getProductsByKeyword($pageNum, $keyword, $itemPerPage)
    {
        return $this->nextAPI->getSearchProducts($pageNum, $keyword, $itemPerPage);
    }

    public function getCategories()
    {
        $categories = NextModel::all();
        return $this->getChildrenCategories($categories);

    }

    public function getCategory($categoryID)
    {
        $category = NextModel::where("catId", "=", $categoryID)->get();
        return $this->getChildrenCategories($category);
    }

    private function getChildrenCategories($categories)
    {
        foreach ($categories as $catKey => $catValue) {
            // get all subcategories for this main category
            $sub = \DB::table($this->subTable)
                ->where("catName", "=", $categories[$catKey]['catId'])
                ->get();
            $categories[$catKey]['sub'] = $sub;
            foreach ($categories[$catKey]['sub'] as $subKey => $subValue) {
                // this subcat id
                $id = $categories[$catKey]['sub'][$subKey]->subcatId;
                // get all subcategories for this subcategory ( making SUB SUB Categories )
                $subSub = \DB::table($this->subSubTable)
                    ->where("nextSubcatsId", "=", $id)
                    ->get();
                $categories[$catKey]['sub'][$subKey]->sub = $subSub;
            }
        }

        return $categories;
    }

    public function getProductUrl($productId)
    {
        $url = \DB::table('nextProductsByCategory')->where('id', $productId)->first();
        return $url->productUrl;
    }

    private function addProfit($products)
    {
        for ($i = 0; $i < count($products['additionalImagesAndVarilables']['Styles']['Fits']); $i++) {
            for ($j = 0; $j < count($products['additionalImagesAndVarilables']['Styles']['Fits'][$i]['Items']); $j++) {
                $colorPrice = str_replace("&#8362", '', $products['additionalImagesAndVarilables']['Styles']['Fits'][$i]
                ['Items'][$j]['FullPrice']);
                $colorPrice = explode('-', $colorPrice);
                $products['additionalImagesAndVarilables']['Styles']['Fits'][$i]['Items'][$j]['FullPrice'] = Util::
                replacePrices($colorPrice, 'ILS', "next");
                for ($z = 0; $z < count($products['additionalImagesAndVarilables']['Styles']['Fits'][$i]['Items'][$j]['Options']);
                     $z++) {
                    $sizePrice = str_replace("&#8362", '', $products['additionalImagesAndVarilables']['Styles']['Fits']
                    [$i]['Items'][$j]['Options'][$z]['Price']);
                    $sizePrice = explode('-', $sizePrice);
                    $products['additionalImagesAndVarilables']['Styles']['Fits'][$i]['Items'][$j]['Options'][$z]['Price']
                        = Util::replacePrices($sizePrice, 'ILS', "next");
                }
            }
        }
        return $products;
    }

    public function getProduct($productUrl)
    {
        $products = $this->nextAPI->getTotalProductData($productUrl);
        $products = $this->addProfit($products);
        return $products;
    }

    function getLatestDeals($menFashion, $womenFashion, $electronic)
    {
        $latestDeals = [$menFashion[15], $womenFashion[15], $electronic[15], $menFashion[18], $womenFashion[18], $electronic[18],
            $menFashion[13], $womenFashion[12], $electronic[11], $menFashion[23], $womenFashion[17], $electronic[19]];
        for ($i = 0; $i < count($latestDeals); $i++) {
            $deal = new \stdClass();
            $deal->discount = ceil(mt_rand(20, 50));
            $deal->salePrice = $latestDeals[$i]->price;
            $deal->imageUrl = $latestDeals[$i]->imageUrl;
            $deal->productUrl = $latestDeals[$i]->productUrl;
            $deal->title = $latestDeals[$i]->title;
            $deal->id = $latestDeals[$i]->id;
            $deal->originalPrice = ceil(str_replace('₪ ', '', $deal->salePrice) * ($deal->discount / 100 + 1));
            $latestDeals[$i] = $deal;
        }
        return $latestDeals;
    }

    public function getSimilarProducts($productUrl)
    {
        $product = \DB::table('nextProductsByCategory')->where('productUrl', $productUrl)->first();
        if (!$product) {
            return null;
        } else {
            return $this->getProductsByCategory($product->categoryID, 24, 2);
        }
    }

    public function getBreadCramb($categoryID)
    {
        $record = \DB::table("nextSubSubcats")->where("subSubcatId", "=", $categoryID)->get()->first();
        if ($record) {
            $result["subSubCategory"] = $record->subSubcatName;
            $subCategory = \DB::table("nextSubcats")->where("subcatId", "=", $record->nextSubcatsId)
                ->select("subcatName")->first();
            $category = \DB::table("nextCats")->where("catId", "=", $record->nextCatsId)
                ->select("catId", "catName")->first();

            $result["subCategory"] = $subCategory->subcatName;
            $result["category"] = $category->catName;
            $result["category_id"] = $category->catId;
        } else {
            $result = null;
        }
        return $result;
    }

    public function getCategoryIdByProduct($productUrl)
    {
        $product = \DB::table('nextProductsByCategory')->where('productUrl', '=', $productUrl)->get()->first();
        if (!$product) {
            return 3;
        } else {
            return $product->categoryID;
        }
    }
}