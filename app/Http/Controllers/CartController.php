<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Checkout;
use App\Models\Ebay\EbayCategories;
use App\Product;
use App\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class CartController extends Controller
{

    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $products = Cart::getProducts();
        $products = Cart::checkNextShipping($products);
        $productAmount = count($products);

        $targetCoupon = isset($_COOKIE['coupon']) ? $_COOKIE['coupon'] : null;
        $productsDiscount = Checkout::getDiscountPrices($targetCoupon, $products);
        $usedCoupon = Checkout::checkCouponCode($targetCoupon);

        if ($productAmount > 0) $products = Cart::tidyProducts($products);
        $jsData["products"] = $products;
        $jsData["usedCoupon"] = $usedCoupon;
        $jsData = Util::includeJsWithData("jsData", $jsData);
        return view("user.cart", [
            'jsData' => $jsData,
            'products' => $products,
            'usedCoupon' => $usedCoupon,
            'productsDiscount' => $productsDiscount,
        ]);
    }

    public function shortcart()
    {
        $products = Cart::getProducts();
        $_SESSION['products'] = $products;

        return view("user.shortcart", ['products' => $products]);
    }

    public function add()
    {
        // get data from ajax
        $product = Input::get("product");

        // calculate number in cart for new item
        $lastProduct = Cart::getLastProduct();

        if (count($lastProduct) == 0 || $lastProduct == null || $lastProduct == "") {
            $newProductId = 0;
        } else {
            if (!isset($_SESSION['user'])) {
                $newProductId = $lastProduct->numInCart + 1;
            } else {
                $newProductId = end($lastProduct)->numInCart + 1;
            }
        }


        // create new product
        $newProduct = new Product($newProductId, $product);

        // put it to db or to session
        if (isset($_SESSION['user'])) {

            Cart::insertProduct($newProduct, $_SESSION['user']->id);

        } else {
            $_SESSION['products'][] = $newProduct;
        }

        // put products to session
        $_SESSION['products'] = Cart::getProducts();


        return count($_SESSION['products']);
    }

    public function remove($id)
    {
        $products = Cart::getProducts();
        foreach ($products as $key => $product) {
            if ($product->numInCart == $id) {
                if (isset($_SESSION['user'])) {
                    Cart::removeProduct($product->numInCart, $_SESSION['user']->id);
                }
                unset($products[$key]);
            }
        }
        $products = Cart::checkNextShipping($products);
        $_SESSION['products'] = $products;

        $targetCoupon = isset($_COOKIE['coupon']) ? $_COOKIE['coupon'] : null;
        $usedCoupon = Checkout::checkCouponCode($targetCoupon);
        return [
            'count' => count($_SESSION['products']),
            'discount' => $usedCoupon,
            'products' => $products,
        ];
    }

    public function edit($id)
    {
        $products = Cart::getProducts();
        foreach ($products as $key => $product) {
            if ($product->numInCart == $id) {
                if (isset($_SESSION['user'])) {
                    Cart::editQuantity($product->numInCart, $_SESSION['user']->id, Input::get("newQuantity"));
                } else{
                    $products[$key]->quantity = Input::get("newQuantity");
                }
            }
        }

        if (isset($products[0]->id)) {
            $products = Cart::getProducts();
        } else {
            $_SESSION['products'] = $products;
        }

        $_SESSION['products'] = Cart::checkNextShipping($products);

        return $products;
    }
}
