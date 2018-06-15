<?php
$dsn = "mysql:host=127.0.0.1;dbname=chipi_laravel;charset=utf8";

$opt = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

$pdo = new PDO($dsn, "chipi", "yami0808", $opt);

$pdo->exec("TRUNCATE TABLE `products`");

$categories = ['tech/cell-phones', 'tech/laptops-netbooks', 'tech/cameras-photo', 'tech/ipads-tablets-ereaders',
    'tech/video-games-consoles', 'tech/phone-cases-accessories', 'tech/vehicle-electronics-gps', 'tech/memory-drives-storage',
    'tech/headphones-portable-audio', 'tech/lenses-filters', 'tech/more-consumer-electronics', 'tech/computer-accessories',
    'fashion/watches', 'fashion/fine-jewelry', 'fashion/beauty', 'fashion/health', 'fashion/mens-shoes-accessories',
    'fashion/womens-shoes-accessories', 'fashion/handbags-and-accessories', 'fashion/mens-clothing', 'fashion/womens-clothing',
    'fashion/kids-stuff', 'fashion/engagement-wedding', 'fashion/more-clothing-shoes-accessories', 'fashion/skin-bath-body',
    'fashion/fragrances', 'fashion/makeup', 'fashion/hair-care-salon', 'fashion/other-health-beauty',
    'fashion/more-jewelry'];

for ($i = 1; $i < count($categories); $i++) {

    $productsByCategory = getDealsTest($categories[$i]);
    
    foreach ($productsByCategory->items as $product) {
    
        $productInfo = getProduct($product->viewModel->listingSummary->listingId);
         
        $shippingInfo = getShipping($product->viewModel->listingSummary->listingId);
       
        if (isShippingToIsrael($productInfo) && $shippingInfo->Ack == "Success") {
       
            $statement = $pdo->prepare("INSERT INTO products(itemId, picture, title,price,shippingPrice,importPrice,categoryId,
feedbackScore) VALUES(:itemId, :picture, :title,:price,:shippingPrice,:importPrice,:categoryId,:feedbackScore)");

            $statement->execute(array(
                'itemId' => $product->viewModel->listingSummary->listingId,
                'picture' => $productInfo->PictureURL[0],
                'title' => $productInfo->Title,
                'price' => $productInfo->CurrentPrice->Value,
                'shippingPrice' => $shippingInfo->ShippingCostSummary->ShippingServiceCost->Value,
                'importPrice' => (isset($shippingInfo->ShippingCostSummary->ImportCharge->Value)) ?
                    $shippingInfo->ShippingCostSummary->ImportCharge->Value : 0,
                'categoryId' => $productInfo->PrimaryCategoryID,
                'feedbackScore' => $productInfo->Seller->FeedbackScore
            ));
            
        }
    }
}
function getDealsTest($categoryName)
{

    $url = "http://www.ebay.com/clt/deals/" . $categoryName . "?_ofs=0&isAjax=true&t=1478501434327&origin-host=http%3A%2F%2Fdeals.ebay.com&_lCat=%27.urlencode(tech/cell-phones).%27&callback=paginationInfiniteRenderItemGrid";
    $html = file_get_contents($url);
    $html = useRegExpression($html, "/\/\*\*\/paginationInfiniteRenderItemGrid\((.+)\)\;/");
    $result = json_decode($html);
    return $result;
}
function getProduct($productID)
{
    if ($productID) {
    
        $productUrl = getProductUrl($productID);
      
        $product = getContent($productUrl);
        return $product->Item;
    }
    return null;
}
function getProductUrl($productID)
{
    $productUrl = "http://open.api.ebay.com/shopping?callname=GetSingleItem&" .
            "responseencoding=JSON&siteid=0&version=967&IncludeSelector=Description,ItemSpecifics,ShippingCosts,Details,Variations" . "&ItemID=" . $productID;
    $productUrl .= "&appid=" . "yossiyah-chipi-PRD-d99eeebc0-fcb21f6d";
    return $productUrl;
}
function getContent($url)
{
    if ($url) {
        $res = file_get_contents($url);
        return json_decode($res);
    }
    return null;
}
function getShippingUrl($productID)
{
    $shippingUrl = "http://open.api.ebay.com/shopping?callname=GetShippingCosts&responseencoding=JSON" . "&appid=" . "yossiyah-chipi-PRD-d99eeebc0-fcb21f6d";
    $shippingUrl .= "&siteid=0&version=981";
    $shippingUrl .= "&ItemID=" . $productID . "&DestinationCountryCode=IL&IncludeDetails=true";
    return $shippingUrl;
}

function getShipping($productID)
{
    $shippingUrl = getShippingUrl($productID);


    return getContent($shippingUrl);
}
function isShippingToIsrael($productsInfo)
{
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
function useRegExpression($string, $regexp)
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