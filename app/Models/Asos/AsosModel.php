<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 24.08.2017
 * Time: 15:18
 */

namespace App\Models\Asos;

use App\Http\API\AsosAPI;
use Illuminate\Database\Eloquent\Model;

class AsosModel extends Model
{
    protected $table = "asosCats";
    protected $subTable = "asosSubcats";
    protected $subSubTable = "asosSubSubcats";
    private $asosAPI;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->asosAPI = new AsosAPI();
    }

    public function getProduct($title, $productId)
    {
        $url = "http://us.asos.com/asos/$title/prd/$productId";
        return $this->asosAPI->getTotalProductData($url);
    }

    public function getProductsByKeywords($keyword, $page,$sort)
    {
        return $this->asosAPI->getProductsByKeywords($keyword, $page,$sort);
    }

    public function getProductsByCategory($categoryId, $page, $sort)
    {
        return $this->asosAPI->getProductsByCategory($categoryId, $page, $sort);
    }

    public function getCategories()
    {
        $categories = AsosModel::all();
        return $this->getChildrenCategories($categories);

    }

    public function getCategory($categoryID)
    {
        $category = AsosModel::where("catId", "=", $categoryID)->get();
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
                    ->where("asosSubcatsId", "=", $id)
                    ->get();
                $categories[$catKey]['sub'][$subKey]->sub = $subSub;
            }
        }

        return $categories;
    }
    public function getBreadCramb($categoryID)
    {
        $record = \DB::table("asosSubSubcats")->where("asosCategoryId", "=", $categoryID)->get()->first();
        if ($record) {
            $result["subSubCategory"] = $record->subSubcatName;
            $subCategory = \DB::table("asosSubcats")->where("subcatId", "=", $record->asosSubcatsId)
                ->select("subcatName")->first();
            $category = \DB::table("asosCats")->where("catId", "=", $record->asosCatsId)
                ->select("catId", "catName")->first();

            $result["subCategory"] = $subCategory->subcatName;
            $result["category"] = $category->catName;
            $result["category_id"] = $category->catId;
        } else {
            $result = null;
        }
        return $result;
    }
}