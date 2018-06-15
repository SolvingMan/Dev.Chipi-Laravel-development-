<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 29.03.2017
 * Time: 12:59
 */

namespace App\Http\API;

use \GuzzleHttp\Client;


class EbayAPI
{
    private $baseUrlForCategories;
    private $baseUrlForProductsByCategory;
    private $baseUrlForProduct;
    private $baseUrlForTradingAPI;
    private $guzzleClient;
    private $appkey;
    private $token;
    private $devkey;
    private $certkey;
    private $baseUrlForHotProducts;
    private $baseUrlForProductsByKeywords;
    private $baseUrlForFilters;
    private $baseUrlForOneDayDeals;
    private $baseUrlForShipping;
    private $baseUrlForSimilarKeyword;

    public function __construct()
    {
        $this->guzzleClient = new Client();
        //$this->appkey = "chipi-chipi-PRD-c05763884-42c7f27c";
        $this->appkey = "yossiyah-chipi-PRD-d99eeebc0-fcb21f6d";
        $this->devkey = "4aa7199a-8b73-484c-afc8-a347aaa86f07";
        $this->certkey = "PRD-057638845e71-ec17-4f92-8a58-4e17";
        $this->token = "AgAAAA**AQAAAA**aAAAAA**YBHeWA**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6ACkIWnDJWHogidj6x9nY+seQ**Nz4DAA**AAMAAA**rCsV5/9Aaf2Q36GnUcXJJK5UYy8D2GB6D89yhm/bwm9/aLztwnOfukinM0gMN05Xj2VQRz2efEtRyf2OuGyg8Ab2qpmAfyMcjCjxOo1ulLkKhi9R1WyzV3ykvvFLPeBXWQmrueotkJxaCnmgyaUShS9+Y9dcDHtYltGdBFJ9NXHrjhSoR0SdYs5+Lh1mMC25XcB4T0xYR4pAa/uwGBjleK1hF6FpT7Leah4cDvTFVZQ8CoF8K3c3TsdIP/3EpePlIo5C+adaiqCfeszpoxl2sfrvz9g4Y3BXqVuTT60bS2ATDK3gCyM1O6bslXyqU5akMjlaoFfCkEcGZbyLTCNycGpGafMDdwvxuSJiosnTgivi112pVXvG/h+gVD6WSwQOvxVP8xheV1jbpUFheLSMicKwoeDM8wRng3h31xl1wEWtmmURvB9FFsYDoUohO3XpqMipTipsCOU1qJ5x0Ts6FRh7DtE4Gl9BEddVJKluOg8Il+ExO/Q7JiEhbkcQ14C90FPzPQJ07KoKSCOlVAIg3D+YGCGwBjtC6AQ3gVpHaFHw3c3oWGiGhKx5XwdayTEb7iwGwMwCB0ZAwySYdWch9kq8FC0xDNvKvRWH4i8fPrai2V2Jl0PplgPEs4ocFmpHdL+kMtPGDPYMNSKFQLzZXYkKU5ZdXBY/kgCk7KMykVYNuUZIf4qbGYAuP1rMDCITLIQOtBAhKFtI+Q1wCe0XyVMpcwPSDDtt88HswS699gfjHPoUqpFJQzxdRbb+k0wK";
        $this->baseUrlForCategories = "http://open.api.ebay.com/Shopping?callname=GetCategoryInfo" .
            "&siteid=0&responseencoding=JSON&version=729&IncludeSelector=ChildCategories";
        $this->baseUrlForProductsByCategory = "http://svcs.ebay.com/services/search/FindingService/v1?OPERATION-NAME" .
            "=findItemsAdvanced&SERVICE-VERSION=1.9.0&RESPONSE-DATA-FORMAT=JSON&REST-PAYLOAD";
        $this->baseUrlForProduct = "http://open.api.ebay.com/shopping?callname=GetSingleItem&" .
            "responseencoding=JSON&siteid=0&version=975&IncludeSelector=Description,ItemSpecifics,ShippingCosts,Details,Variations";
        $this->baseUrlForSimilarProduct = "http://svcs.ebay.com/MerchandisingService?OPERATION-NAME=" .
            "getSimilarItems&SERVICE-NAME=MerchandisingService&SERVICE-VERSION=1.4.0" .
            "&RESPONSE-DATA-FORMAT=JSON&REST-PAYLOAD";
        $this->baseUrlForTradingAPI = "https://api.ebay.com/ws/api.dll";
        $this->baseUrlForTradingAPISoap = "https://api.sandbox.ebay.com/wsapi";
        $this->baseUrlForHotProducts = "http://svcs.ebay.com/MerchandisingService?" .
            "SERVICE-NAME=MerchandisingService&SERVICE-VERSION=1.1.0&RESPONSE-DATA-FORMAT=JSON&REST-PAYLOAD&maxResults=42";
        $this->baseUrlForProductsByKeywords = "http://svcs.ebay.com/services/search/FindingService/v1?OPERATION-NAME=findItemsByKeywords&" .
            "SERVICE-VERSION=1.9.0&RESPONSE-DATA-FORMAT=JSON&REST-PAYLOAD";
        $this->baseUrlForFilters = "http://svcs.ebay.com/services/search/FindingService/v1?OPERATION-NAME" .
            "=findItemsByKeywords&SERVICE-VERSION=1.9.0&RESPONSE-DATA-FORMAT=JSON&REST-PAYLOAD";
        $this->baseUrlForOneDayDeals = "http://www.ebay.com/rps/feed/v1.1/ebay-it?limit=201";
        $this->baseUrlForShipping = "http://open.api.ebay.com/shopping?callname=GetShippingCosts&responseencoding=JSON";
        $this->baseUrlForSimilarKeyword = "http://svcs.ebay.com/services/search/FindingService/v1?OPERATION-NAME=" .
            "getSearchKeywordsRecommendation&SERVICE-VERSION=1.0.0&RESPONSE-DATA-FORMAT=JSON&REST-PAYLOAD";
    }

    private function useRegExpression($string, $regexp)
    {
        if ($string || $regexp) {
            $match = [];
            $result = '';
            if (preg_match($regexp, $string, $match)) {
                $result = $match[1];
            }
            return $result;
        }
        return null;
    }

    private function getSimilarKeywordUrl($keywords)
    {
        $similarKeywordUrl = $this->baseUrlForSimilarKeyword . "&SECURITY-APPNAME=" . $this->appkey;
        $similarKeywordUrl .= "&keywords=" . $keywords;
        return $similarKeywordUrl;
    }

    private function getProductsByKeywordsUrl($keywords, $entriesPerPage, $page, $filterData, $sortOrder)
    {
        $productsByKeywordsUrl = $this->baseUrlForProductsByKeywords . "&SECURITY-APPNAME=" . $this->appkey;
        //dd($keywords);
        $productsByKeywordsUrl .= "&keywords=" . $keywords;
        $productsByKeywordsUrl .= "&paginationInput.entriesPerPage=" . $entriesPerPage;
        $productsByKeywordsUrl .= "&paginationInput.pageNumber=" . $page;
        if ($filterData) {
            $i = 0;
            foreach ($filterData as $filterTitle => $filterName) {
                $filterTitle = str_replace(" ", "+", $filterTitle);
                $filterTitle = str_replace("&", "%26", $filterTitle);
                $productsByKeywordsUrl .= "&aspectFilter(" . $i . ").aspectName=" . $filterTitle;
                $filterName = str_replace(" ", "+", $filterName);
                $filterName = str_replace("&", "%26", $filterName);
                $filterName = explode("|", $filterName);
                $j = 0;
                foreach ($filterName as $name) {
                    $productsByKeywordsUrl .= "&aspectFilter(" . $i . ").aspectValueName(" . $j . ")=" . $name;
                    $j++;
                }
                $i++;
            }
        }
        if ($sortOrder) {
            $productsByKeywordsUrl .= "&sortOrder=" . $sortOrder;
        }
        $productsByKeywordsUrl .= "&itemFilter(0).name=FeedbackScoreMin&itemFilter(0).value(0)=750";
        $productsByKeywordsUrl .= "&itemFilter(1).name=HideDuplicateItems&itemFilter(1).value(0)=true";
        $productsByKeywordsUrl .= "&itemFilter(2).name=AvailableTo&itemFilter(2).value(0)=IL";
        $productsByKeywordsUrl .= "&itemFilter(3).name=ListingType&itemFilter(3).value(0)=Classified" .
            "&itemFilter(3).value(1)=FixedPrice&itemFilter(3).value(2)=StoreInventory";
        //dd($productsByKeywordsUrl);
        return $productsByKeywordsUrl;
    }

    private function getHotProductsUrl($method, $categoryID)
    {
        $hotProductsUrl = $this->baseUrlForHotProducts . "&CONSUMER-ID=" . $this->appkey;
        $hotProductsUrl .= "&OPERATION-NAME=" . $method;
        $hotProductsUrl .= "&categoryId=" . $categoryID;
        return $hotProductsUrl;
    }

    private function getCategoryChildrenUrl($categoryID)
    {
        $categoryUrl = $this->baseUrlForCategories . "&CategoryID=" . $categoryID;
        $categoryUrl .= "&appid=" . $this->appkey;
        return $categoryUrl;
    }

    private function getProductUrl($productID)
    {
        $productUrl = $this->baseUrlForProduct . "&ItemID=" . $productID;
        $productUrl .= "&appid=" . $this->appkey;
        return $productUrl;
    }

    private function getSimilarProductUrl($productID, $numberItems = 5)
    {
        $similarProductUrl = $this->baseUrlForSimilarProduct . "&itemId=" . $productID;
        $similarProductUrl .= "&CONSUMER-ID=" . $this->appkey;
        $similarProductUrl .= "&maxResults=" . $numberItems;
        return $similarProductUrl;
    }

    private function getFiltersUrl($keyword)
    {
        $filterUrl = $this->baseUrlForFilters . "&SECURITY-APPNAME=" . $this->appkey;
        $filterUrl .= "&keywords=" . $keyword;
        $filterUrl .= "&outputSelector=AspectHistogram";
        //dd($filterUrl);
        return $filterUrl;
    }

    private function getProductsByCategoryUrl($categoryID, $entriesPerPage, $pageNumber, $filterData, $sortOrder)
    {
        $productsByCategoryUrl = $this->baseUrlForProductsByCategory . "&SECURITY-APPNAME=" . $this->appkey;
        $productsByCategoryUrl .= "&categoryId=" . $categoryID;

        if ($entriesPerPage) {
            $productsByCategoryUrl .= "&paginationInput.entriesPerPage=" . $entriesPerPage;
        }
        if ($pageNumber) {
            $productsByCategoryUrl .= "&paginationInput.pageNumber=" . $pageNumber;
        }
        if ($filterData) {
            $i = 0;
            foreach ($filterData as $filterTitle => $filterName) {
                $filterTitle = str_replace(" ", "+", $filterTitle);
                $filterTitle = str_replace("&", "%26", $filterTitle);
                $productsByCategoryUrl .= "&aspectFilter(" . $i . ").aspectName=" . $filterTitle;
                $filterName = str_replace(" ", "+", $filterName);
                $filterName = str_replace("&", "%26", $filterName);
                $filterName = explode("|", $filterName);
                $j = 0;
                foreach ($filterName as $name) {
                    $productsByCategoryUrl .= "&aspectFilter(" . $i . ").aspectValueName(" . $j . ")=" . $name;
                    $j++;
                }
                $i++;
            }
        }
        if ($sortOrder) {
            $productsByCategoryUrl .= "&sortOrder=" . $sortOrder;
        }
        $productsByCategoryUrl .= "&itemFilter(0).name=FeedbackScoreMin&itemFilter(0).value(0)=750";
        $productsByCategoryUrl .= "&itemFilter(1).name=HideDuplicateItems&itemFilter(1).value(0)=true";
        $productsByCategoryUrl .= "&itemFilter(2).name=AvailableTo&itemFilter(2).value(0)=IL";
        $productsByCategoryUrl .= "&itemFilter(3).name=ListingType&itemFilter(3).value(0)=Classified" .
            "&itemFilter(3).value(1)=FixedPrice&itemFilter(3).value(2)=StoreInventory";
        //dd($productsByCategoryUrl);
        return $productsByCategoryUrl;
    }

    private function getOptionsForRequestEbay($methodName, $body)
    {
        return array('http' =>
            array(
                'method' => 'POST',
                'header' =>
                    "X-EBAY-API-CALL-NAME: " . $methodName . "\r\n" .
                    "X-EBAY-API-APP-NAME: " . $this->appkey . "\r\n" .
                    "X-EBAY-API-DEV-NAME: " . $this->devkey . "\r\n" .
                    "X-EBAY-API-CERT-NAME: " . $this->certkey . "\r\n" .
                    "Content-Type: text/xml\r\n" .
                    "X-EBAY-API-SITEID: 0\r\n" .
                    "X-EBAY-API-COMPATIBILITY-LEVEL: 971\r\n",
                'content' => $body,
                'timeout' => 60
            )
        );
    }

    private function getContent($url)
    {
        if ($url) {
            $client = new \GuzzleHttp\Client();
            $res = $client->request('GET', $url);
            return json_decode($res->getBody());
        }
        return null;
    }

    public function getSimilarKeyword($keywords)
    {
        $similarKeywordUrl = $this->getSimilarKeywordUrl($keywords);
        $similarKeyword = $this->getContent($similarKeywordUrl);
        $similarKeyword = $similarKeyword->getSearchKeywordsRecommendationResponse[0];
        dd($similarKeyword);
    }

    public function getProductsByKeywords($keywords, $entriesPerPage = 42, $page = 1, $filterData = false, $sortOrder = false)
    {
        $productsByKeywordsUrl = $this->getProductsByKeywordsUrl($keywords, $entriesPerPage, $page, $filterData, $sortOrder);
        $productsByKeywords = $this->getContent($productsByKeywordsUrl);
        $productsByKeywords = $productsByKeywords->findItemsByKeywordsResponse[0];
        return $productsByKeywords;
    }

    public function getMostWatchedItems($categoryID)
    {
        $mostWatchedItemsUrl = $this->getHotProductsUrl("getMostWatchedItems", $categoryID);
        $mostWatchedItems = $this->getContent($mostWatchedItemsUrl);
        $mostWatchedItems = $mostWatchedItems->getMostWatchedItemsResponse->itemRecommendations->item;
        return $mostWatchedItems;
    }

    public function getFiltersForSearch($keyword)
    {
        if ($keyword) {
            $filterUrl = $this->getFiltersUrl($keyword);
            $result = $this->getContent($filterUrl);

            if (isset($result->findItemsByKeywordsResponse[0]->aspectHistogramContainer[0])) {
                return $result->findItemsByKeywordsResponse[0]->aspectHistogramContainer[0];
            }
            return null;
        }
        return null;
    }

    public function getTopSellingProducts()
    {
        $topSellingProductsUrl = $this->getHotProductsUrl("getTopSellingProducts");
        $topSellingProducts = $this->getContent($topSellingProductsUrl);
        $topSellingProducts = $topSellingProducts->getTopSellingProductsResponse->productRecommendations->product;
        return $topSellingProducts;
    }

    public function getSimilarProduct($productID, $numberItems = 5)
    {
        if ($productID) {
            $similarProductUrl = $this->getSimilarProductUrl($productID, $numberItems);
            return $this->getContent($similarProductUrl);
        }
        return null;
    }

    public function getProduct($productID)
    {
        if ($productID) {
            $productUrl = $this->getProductUrl($productID);
            $product = $this->getContent($productUrl);
            if (isset($product->Item)) {
                return $product->Item;
            } else {
                return response()->view('errors.404', [], 404);
            }

        }
        return null;
    }

    public function getProductsByCategory($categoryID, $entriesPerPage = 42, $pageNumber = 1, $filterData = false, $sortOrder = false)
    {
        if ($categoryID) {
            $productsByCategoryUrl = $this->getProductsByCategoryUrl($categoryID, $entriesPerPage, $pageNumber, $filterData, $sortOrder);

            $result = $this->getContent($productsByCategoryUrl);
            return $result->findItemsAdvancedResponse[0];
        }
        return null;
    }

    public function getCategoryChildren($categoryID = -1)
    {
        $categoryUrl = $this->getCategoryChildrenUrl($categoryID);
        return $this->getContent($categoryUrl);
    }

    public function getCategories()
    {
        $body =
            "<?xml version=\"1.0\" encoding=\"utf-8\"?><GetCategoriesRequest xmlns=\"urn:ebay:apis:eBLBaseComponents\">" .
            "<RequesterCredentials><eBayAuthToken>" . $this->token . "</eBayAuthToken></RequesterCredentials>" .
            "<CategorySiteID>0</CategorySiteID><DetailLevel>ReturnAll</DetailLevel><LevelLimit>2</LevelLimit></GetCategoriesRequest>";
        $opts = $this->getOptionsForRequestEbay("GetCategories", $body);
        $context = stream_context_create($opts);
        $url = $this->baseUrlForTradingAPI;
        $result = file_get_contents($url, false, $context);
        $result = new \SimpleXMLElement($result);
        return $result;
    }

    public function getOneDayDeals()
    {
        $url = $this->baseUrlForOneDayDeals;
        $oneDayDeals = file_get_contents($url);
        $oneDayDeals = new \SimpleXMLElement($oneDayDeals);
        return $oneDayDeals;
    }

    public function getShippingUrl($productID)
    {
        $shippingUrl = $this->baseUrlForShipping . "&appid=" . $this->appkey;
        $shippingUrl .= "&siteid=0&version=981";
        $shippingUrl .= "&ItemID=" . $productID . "&DestinationCountryCode=IL&IncludeDetails=true";
        return $shippingUrl;
    }

    public function getShipping($productID)
    {
        $shippingUrl = $this->getShippingUrl($productID);


        return $this->getContent($shippingUrl);
    }

    public function getShippingOrigin($productID)
    {
        $body =
            "<?xml version=\"1.0\" encoding=\"utf-8\"?><GetItemShippingRequest xmlns=\"urn:ebay:apis:eBLBaseComponents\">" .
            "<RequesterCredentials><eBayAuthToken>" . $this->token . "</eBayAuthToken></RequesterCredentials>" .
            "<ItemID>$productID</ItemID><DestinationCountryCode>IL</DestinationCountryCode>" .
            "</GetItemShippingRequest>";
        $opts = $this->getOptionsForRequestEbay("GetItemShipping", $body);
        $context = stream_context_create($opts);
        $url = $this->baseUrlForTradingAPI;
        $result = file_get_contents($url, false, $context);
        $result = new \SimpleXMLElement($result);
        return $result;
    }

    public function getDealsTest($categoryName)
    {
        $url = "http://www.ebay.com/clt/deals/" . $categoryName . "?_ofs=0&isAjax=true&t=1478501434327&origin-host=http%3A%2F%2Fdeals.ebay.com&_lCat=%27.urlencode(tech/cell-phones).%27&callback=paginationInfiniteRenderItemGrid";
        $html = file_get_contents($url);
        $html = $this->useRegExpression($html, "/\/\*\*\/paginationInfiniteRenderItemGrid\((.+)\)\;/");
        $result = json_decode($html);
        return $result;
    }

    public function getFeedback($productID)
    {
        $entriesPerPage = 50;
        if ($productID) {
            $body =
                "<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
                "<GetFeedbackRequest xmlns=\"urn:ebay:apis:eBLBaseComponents\">" .
                "<RequesterCredentials><eBayAuthToken>" . $this->token . "</eBayAuthToken></RequesterCredentials>" .
                "<Pagination>" .
                "<EntriesPerPage>" . $entriesPerPage . "</EntriesPerPage>" .
                "<PageNumber>" . 1 . "</PageNumber>" .
                "</Pagination>" .
//               "<Pagination><EntriesPerPage>25</EntriesPerPage><PageNumber>2</PageNumber></Pagination>" .
                "<DetailLevel>ReturnAll</DetailLevel>" .
                "<ErrorLanguage>en_US</ErrorLanguage>" .
                "<WarningLevel>High</WarningLevel><ItemID>" . $productID . "</ItemID>" .
                "</GetFeedbackRequest>";
            $opts = $this->getOptionsForRequestEbay("GetFeedback", $body);
            $context = stream_context_create($opts);
            $url = $this->baseUrlForTradingAPI;
            $result = file_get_contents($url, false, $context);
            $result = new \SimpleXMLElement($result);
            return $result;
        }
        return null;
    }

    public function getShippingLocationDetails()
    {
        $body =
            "<?xml version=\"1.0\" encoding=\"utf-8\"?> <GeteBayDetailsRequest xmlns=\"urn:ebay:apis:eBLBaseComponents\">" .
            "<RequesterCredentials><eBayAuthToken>" . $this->token . "</eBayAuthToken></RequesterCredentials>" .
            "<DetailName>ExcludeShippingLocationDetails</DetailName></GeteBayDetailsRequest>";
        $opts = $this->getOptionsForRequestEbay("GeteBayDetails", $body);
        $context = stream_context_create($opts);
        $url = $this->baseUrlForTradingAPI;
        //dd($context);
        $result = file_get_contents($url, false, $context);
        $result = new \SimpleXMLElement($result);
        return $result;
    }

    public function isShippingToIsrael($productsInfo)
    {
        //dd($productsInfo);
        foreach ($productsInfo->ShipToLocations as $ship) {
            if ($ship == "IL") {
                return true;
            }
            if ($ship == "Middle East" || $ship == "Worldwide" || $ship == "Asia") {
                if (isset($productsInfo->ExcludeShipToLocation)) {
                    foreach ($productsInfo->ExcludeShipToLocation as $excludeShip) {
                        if ($excludeShip == "IL" || $excludeShip == "Middle East" || $excludeShip == "Asia") {
                            return false;
                        }
                    }
                }
                return true;
            }
        }
        return false;
    }
}