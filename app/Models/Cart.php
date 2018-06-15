<?php

namespace App\Models;

use App\Product;
use App\Util;
use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Collection;
use Symfony\Component\Finder\Expression\Regex;

class Cart extends Model
{
    // tidying products for cart
    public static function tidyProducts($products)
    {
        foreach ($products as $key => $product) {
            if (!is_object($products[$key]->selectedSku)) {
                if (is_string($products[$key]->selectedSku)) {
                    if ($products[$key]->selectedSku[0] == '"') {
                        $products[$key]->selectedSku = json_decode(self::escapeJsonString(trim($products[$key]->selectedSku, '"')));
                    } else {
                        $products[$key]->selectedSku = json_decode($products[$key]->selectedSku);
                    }
                }
            }
        }

        return $products;
    }

    public static function escapeJsonString($string)
    {
        for ($i = 0; $i < strlen($string) - 1; $i++) {
            if ($string[$i] == '\\' && $string[$i + 1] != '\\') {
                $string = substr_replace($string, '', $i, 1);
            }
        }
        return $string;
    }

    public static function getLastProduct()
    {
        $products = self::getProducts();

        return end($products);
    }

    public static function getProducts($userId = "")
    {

        if ($userId != "") {
            return DB::table('cartProduct')
                ->where('userId', "=", $userId)
                ->where('status', "=", \Config::get('enums.cart_status.CART'))
                ->get();
        }

        if (isset($_SESSION['user'])) {
            return DB::table('cartProduct')
                ->where('userId', "=", $_SESSION['user']->id)
                ->where('status', "=", \Config::get('enums.cart_status.CART'))
                ->get();
        } else {
            return isset($_SESSION['products']) ? $_SESSION['products'] : [];
        }
    }

    public static function getHistoryProducts()
    {
        $products = DB::table('orderSummary')
            ->where('summaryUserId', "=", $_SESSION['user']->id)
            ->orderBy('summaryId', 'desc')
            ->get();

        return $products->count() > 0 ? $products : new Collection();
    }

    public static function getOrderProducts($summaryId)
    {
        return DB::table('orderDeatils')
            ->where('orderSummaryId', "=", $summaryId)
            ->get();
    }

    public static function getCurrentHistory()
    {
        $history = self::getHistoryProducts();
        foreach ($history as $summary) {
            $summary->statusText = self::getSummaryStatusText($summary->summaryStatus);
            $summary->products = self::getOrderProducts($summary->summaryId);

            foreach ($summary->products as $product) {
                $product->orderProductOptions = json_decode($product->orderProductOptions);
            }
        }
        return $history;
    }

    public static function tidyHistory($history)
    {
        if (count($history) == 0) return $history;

        foreach ($history as $summary) {
            $format = 'd/m/Y';
            $summary->summaryDate = Util::convertDate($summary->summaryDate, $format);
            $summary->summaryEsitmatedDate = Util::convertDate($summary->summaryEsitmatedDate, $format);
        }

        return $history;
    }

    public static function getSummaryStatusText($status)
    {
        $arara = [
            0 => "הועבר למוכר",
            2 => "נשלח מהספק",
            5 => "הזמנה מבוטלת",
            1 => "הוזמנה מהספקים",
            6 => "ממתין לתשובת הלקוח",
            7 => "נשלחה בחלקה - חלק מהמוצרים ממתינים לתשובת הלקוח",
            8 => "הזמנה סגורה",
        ];

        return $arara[$status];
    }

//    public static function getOrderedProducts()
//    {
//        $products = DB::table('cartProduct')
//            ->where('userId', "=", $_SESSION['user']->id)
//            ->where('status', "=", \Config::get('enums.cart_status.ORDERED'))
//            ->get();
////        dd($products);
//
//        return $products->count() > 0 ? $products : new Collection();
//    }

    public static function putProductsToDB($userId)
    {
        if (isset($_SESSION['products'])) {

            // find the max num in cart from ones that are in db
            $maxNumInCart = 0;
            $products = Cart::getProducts();
            foreach ($products as $product) {
                if ($product->numInCart > $maxNumInCart) $maxNumInCart = $product->numInCart;
            }

            // pun products from session to db
            foreach ($_SESSION['products'] as $product) {
                $product->numInCart = ++$maxNumInCart;
                self::insertProduct($product, $userId);
            }
        }
    }

    /**
     * @param $product
     * @param $userId
     */
    public static function insertProduct($product, $userId)
    {
        // get clone to not harm original object
        if (is_object($product)) {
            $product = clone $product;
        }

        // add some data
        $product->userId = $userId;
        $product->selectedSku = json_encode($product->selectedSku);

//        // cast to array to be available for db slick addition
//        $productArray = (array)$product;
//        $productArray['shippingCompany'] = $productArray['shippingCompany'][0];
//
//        // remove field that is not in db (probably remove later)
//        $removableFields = ['id', 'totalCost'];
//        foreach ($removableFields as $field) {
//            if (isset($productArray[$field])) {
//                unset($productArray[$field]);
//            }
//        }
//        dd($productArray);
        DB::insert("insert into cartProduct (productID,numInCart,userId,productUrl,sitename,title,productPrice,
originalProductPrice,shippingPrice,originalShippingPrice,shippingCompany,availableQuantity,selectedSku,image,
quantity) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",[$product->productID,$product->numInCart,$product->userId,
            $product->productUrl,$product->sitename,$product->title,$product->productPrice,$product->originalProductPrice,
            $product->shippingPrice,$product->originalShippingPrice,$product->shippingCompany,$product->availableQuantity,
            $product->selectedSku,$product->image,$product->quantity]);
        //DB::table('cartProduct')->insert($productArray);

    }

    public static function removeProduct($numInCart, $userId)
    {
        DB::delete('delete from cartProduct where numInCart=? and userId=?', [$numInCart, $userId]);
    }

    public static function editQuantity($numInCart, $userId, $newQuantity)
    {
//        DB::table('cartProduct')->where('numInCart', "=", $numInCart)
//            ->where("userId", "=", $userId)->update(["quantity" => $newQuantity]);
        DB::update('update cartProduct set quantity=? where numInCart=? and userId=?', [$newQuantity, $numInCart, $userId]);
    }

    public static function refreshInDB($products)
    {
        foreach ($products as $product) {
//            DB::table('cartProduct')->where('id', '=', $product->id)->update([
//                'productPrice' => $product->productPrice,
//                'originalProductPrice' => $product->originalProductPrice,
//                'shippingPrice' => $product->shippingPrice,
//                'originalShippingPrice' => $product->originalShippingPrice,
//                'availableQuantity' => $product->availableQuantity
//            ]);
            DB::update('update cartProduct set productPrice=?,originalProductPrice=?,shippingPrice=?,
originalShippingPrice=?,availableQuantity=? where id=?', [$product->productPrice, $product->originalProductPrice,
                $product->shippingPrice, $product->originalShippingPrice, $product->availableQuantity, $product->id
            ]);
        }
    }

    public static function checkNextShipping($products)
    {
        $totalSum = 0;
        foreach ($products as &$product) {
            if ($product->sitename == "next") {
                $product->shippingPrice = 0;
                $totalSum += $product->productPrice * $product->quantity;
            }
        }

        if ($totalSum < 150) {
            foreach ($products as &$product) {
                if ($product->sitename == "next") {
                    $product->shippingPrice = 20;
                    break;
                }
            }
        }
        //dd($products);
        if (!is_array($products)) {
            foreach ($products as $product) {
                if (isset($product->id)) {
                    Cart::refreshInDB($products);
                    break;
                }
            }
        }
        return $products;
    }
}
