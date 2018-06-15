<?php

namespace App;


class Product
{
    public $numInCart;
    public $productUrl;
    public $sitename;
    public $title;
    public $productPrice;
    public $originalProductPrice;
    public $shippingPrice;
    public $originalShippingPrice;
    public $shippingCompany;
    public $availableQuantity;
    public $selectedSku;
    public $image;
    public $quantity;
    public $productID;


    function __construct($numInCart = null, $product = null)
    {
        $this->sitename = $product['sitename'];
        switch ($this->sitename) {
            case "aliexpress":
                $this->aliex($numInCart, $product);
                break;
            case "ebay":
                $this->ebay($numInCart, $product);
                break;
            case "next":
                $this->next($numInCart, $product);
                break;
            case "asos":
                $this->asos($numInCart, $product);
                break;
            default:
                break;
        }
    }

    function aliex($numInCart, $product)
    {
        $this->productID = $product['productID'];
        $this->numInCart = $numInCart;
        $this->productUrl = $product['productURL'];
        $this->title = $product['title'];
        $this->productPrice = $product['mainPrice'];
        $this->originalProductPrice = $product['original_mainPrice'];
        $this->shippingPrice = $product['shipping']['cost'];
        $this->originalShippingPrice = $product['shipping']['original_cost'];
        $this->shippingCompany = $product['shipping']['company'];
        $this->availableQuantity = $product['selectedSkuVariant']['skuVal']['availQuantity'];
        $this->selectedSku = $product['selectedSku'];
        $this->image = $product['image'];
        $this->quantity = $product['quantity'];
    }

    function ebay($numInCart, $product)
    {
        $this->productID = $product['productID'];
        $this->numInCart = $numInCart;
        $this->productUrl = $product['productURL'];
        $this->title = $product['title'];
        $this->productPrice = $product['mainPrice'];
        $this->originalProductPrice = $product['original_mainPrice'];
        $this->originalShippingPrice = $product['original_shippingPrice'];
        $this->shippingPrice = $product['shippingPrice'];
        $this->shippingCompany = $product['shippingCompany'];
        $this->availableQuantity = $product['availableQuantity'];
        $this->selectedSku = $product['selectedSku'];
        $this->image = $product['image'];
        $this->quantity = $product['quantity'];
    }
    function next($numInCart, $product)
    {
        $this->productID = $product['productID'];
        $this->numInCart = $numInCart;
        $this->productUrl = $product['productURL'];
        $this->title = $product['title'];
        $this->productPrice = $product['mainPrice'];
        $this->originalProductPrice = $product['original_mainPrice'];
        $this->originalShippingPrice = $product['original_shippingPrice'];
        $this->shippingPrice = $product['shippingPrice'];
        $this->shippingCompany = $product['shippingCompany'];
        $this->availableQuantity = $product['availableQuantity'];
        $this->selectedSku = $product['selectedSku'];
        $this->image = $product['image'];
        $this->quantity = $product['quantity'];
    }
    function asos($numInCart, $product)
    {
        $this->productID = $product['productID'];
        $this->numInCart = $numInCart;
        $this->productUrl = $product['productURL'];
        $this->title = $product['title'];
        $this->productPrice = $product['mainPrice'];
        $this->originalProductPrice = $product['original_mainPrice'];
        $this->originalShippingPrice = $product['original_shippingPrice'];
        $this->shippingPrice = $product['shippingPrice'];
        $this->shippingCompany = $product['shippingCompany'];
        $this->availableQuantity = $product['availableQuantity'];
        $this->selectedSku = $product['selectedSku'];
        $this->image = $product['image'];
        $this->quantity = $product['quantity'];
    }
}