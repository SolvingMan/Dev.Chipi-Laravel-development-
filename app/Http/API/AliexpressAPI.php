<?php

namespace App\Http\API;

use App\Util;
use Mockery\Exception;
use Symfony\Component\DomCrawler\Crawler;
use \GuzzleHttp\Client;

class AliexpressAPI
{
    private $appkey;
    private $baseApiUrl;
    private $client;
    private $baseDescriptionUrl;
    private $baseShippingUrl;
    private $baseFeedbackUrl;
    private $productUrl;
    private $html;
    private $baseSellerUrl;

    public function __construct($productUrl = "")
    {
        $this->appkey = "16800";
        $this->client = new Client();
        $this->baseApiUrl = "http://gw.api.alibaba.com/openapi/param2/2/portals.open/api.";
        $this->baseDescriptionUrl = "https://aliexpress.com/getDescModuleAjax.htm?productId=";
        $this->baseShippingUrl = "https://freight.aliexpress.com/ajaxFreightCalculateService.htm?" .
            "productid=@prod&count=1&currencyCode=RUB&sendGoodsCountry=CN&country=IL&province=" .
            "&city=&abVersion=1&_=1490710741647";
        $this->baseFeedbackUrl = "http://feedback.aliexpress.com/display/productEvaluation.htm" .
            "?productId=@prod&ownerMemberId=@own&companyId=@comp&memberType=seller&startValidDate=&i18n=true";
        $this->baseSellerUrl = "https://feedback.aliexpress.com/display/evaluationDsrAjaxService.htm?ownerAdminSeq=";
        $this->productUrl = $productUrl;

        // old version
        $this->html = $productUrl == "" ? "" : $this->get_web_page($productUrl)["content"];
    }

    function getWidgetPhaseArray()
    {
        $url = "https://flashdeals.aliexpress.com/en.htm?spm=2114.11010108.21.2.35f7175HaKIzq";
        $asosApi = new AsosAPI();
        $html = $asosApi->getContent($url);
        $crawler = new Crawler();
        $crawler->addHtmlContent($html, 'UTF-8');
        $widgetArray = [];
        $phaseArray = [];
        for ($i = 1; $i <= 4; $i++) {
            $widgetId = $crawler->filter(".deals-horizontal li:nth-child($i)")->attr("id");
            $phase = $crawler->filter(".deals-horizontal li:nth-child($i)")->attr("data-phase");
            $widgetId = str_replace("#", '', trim($widgetId));
            $widgetArray[] = $widgetId;
            $phaseArray[] = $phase;
        }
        $data["widgetArray"] = $widgetArray;
        $data["phaseArray"] = $phaseArray;
        //dd($data);
        return $data;
    }

    function getAliDeals($limit, $offset, $widgetId,$phase)
    {
        $url = "https://gpsfront.aliexpress.com/queryGpsProductAjax.do?widget_id=$widgetId&limit=$limit&offset=$offset" .
            "&phase=$phase&_=1505296031789";
        //dd($url);
        $response = file_get_contents($url);
        $products = json_decode($response);

        return $products;
    }

    function get_web_page($url)
    {
        $proxy = [
            "ip" => "69.39.224.128",
            "port" => "80",
            "username" => "chipi1",
            "password" => "kSUnNtJ_cE"
        ];
        $filepath = '/tmp/cookies.txt';
        // $filepath = '/home/chipi/tmp/cookies.txt';
        $options = array(
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING => "",       // handle all encodings
            CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36", // who am i
            CURLOPT_AUTOREFERER => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 1200000,      // timeout on connect
            CURLOPT_TIMEOUT => 1200000,      // timeout on response
            CURLOPT_MAXREDIRS => 10,       // stop after 10 redirects
            CURLOPT_SSL_VERIFYPEER => false,     // Disabled SSL Cert checks
            CURLOPT_HTTPPROXYTUNNEL => true,
            CURLOPT_PROXY => $proxy['ip'],
            CURLOPT_PROXYUSERPWD => "{$proxy['username']}:{$proxy['password']}",
            CURLOPT_PROXYPORT => $proxy['port'],
            CURLOPT_COOKIEJAR => $filepath,
            CURLOPT_COOKIEFILE => $filepath
        );

        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        $content = curl_exec($ch);
        $err = curl_errno($ch);
        $errmsg = curl_error($ch);
        $header = curl_getinfo($ch);
        curl_close($ch);

        $header['errno'] = $err;
        $header['errmsg'] = $errmsg;
        $header['content'] = $content;
//        dd($header);

        return $header;
    }

    private function makeCurlCall($apiCall)
    {
        if ($apiCall) {
            $ch = curl_init($apiCall);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($ch);
            curl_close($ch);
            return $result;
        }
        return null;
    }

    private function getUrlToAPI($methodName)
    {
        if ($methodName) {
            return $this->baseApiUrl . $methodName . '/' . $this->appkey . "?";
        }
        return null;
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

    private function parseProductSKU($productSkuList)
    {
//dd($productSkuList);
        if ($productSkuList) {
            $result = null;
            foreach ($productSkuList as $k => $productSku) {
                $this->workWithMatch($productSku, "dataSkuId", $result, $k, "/.+data-sku-id=\"(\d+)\".+/");
                $this->workWithMatch($productSku, "title", $result, $k, "/.+\<span\>(.+)\<\/span\>.+/");
                $this->workWithMatch($productSku, "title", $result, $k, "/.+title=\"(.+?)\"/");
                $this->workWithMatch($productSku, "src", $result, $k, "/.+src=\"(.+?)\"/");
                $this->workWithMatch($productSku, "bigpic", $result, $k, "/.+bigpic=\"(.+)\".+/");
            }
            return $result;
        }
        return null;
    }

    private function workWithMatch($productSku, $attrName, &$result, $nameList, $regularExpression)
    {
        $match = [];
        if (preg_match_all($regularExpression, $productSku, $match[])) {
            for ($i = 0; $i < count($match[0][1]); $i++) {
                $result[$nameList][$i][$attrName] = $match[0][1][$i];
            }
        }
    }

    private function getSellerInfo($html)
    {
        if ($html) {
            $ownerID = $this->getOwnerMemberID($html);
            $productUrl = $this->baseSellerUrl . $ownerID;
            $sellerInfo = file_get_contents($productUrl);
            return json_decode($sellerInfo);
        }
        return null;
    }

    public function getArraySkuProducts($html = "")
    {
        if ($html == "") {
            $html = $this->html;
        }
        if ($html) {
            $skuArray = $this->useRegExpression($html, '/skuProducts=(\[.+\}\]);/');
            return json_decode($skuArray);
        }
        return null;
    }

    private function getOrderNum($crawler)
    {
        if ($crawler) {
            return $this->checkResultParser($crawler->filter("#j-order-num"));
        }
        return null;
    }

    private function getProductSKU($crawler)
    {
        if ($crawler) {
            $productSKU = $crawler->filter("#j-product-info-sku");
            $productSkuList = [];
            foreach ($productSKU->children() as $product) {
                $crawlerNode = new Crawler($product);
                $skuName = $this->checkResultParser($crawlerNode
                    ->filter("dt.p-item-title"));

                $productSkuList[$skuName] = $this->checkResultParser($crawlerNode
                    ->filter("dd.p-item-main ul"));
//Id can not always begin with 1

            }
            return $productSkuList == null ? null : $this->parseProductSKU($productSkuList);
        }
        return null;
    }

    private function getFeedbackUrl($html)
    {
        if ($html) {
            $productID = $this->getProductID($html);
            $ownerID = $this->getOwnerMemberID($html);
            $companyID = $this->getCompanyID($html);
            $feedbackUrl = str_replace("@prod", $productID, $this->baseFeedbackUrl);
            $feedbackUrl = str_replace("@own", $ownerID, $feedbackUrl);
            $feedbackUrl = str_replace("@comp", $companyID, $feedbackUrl);
            return $feedbackUrl;
        }
        return null;
    }

    private function getShippingUrl($html)
    {
        if ($html) {
            $productID = $this->getProductID($html);
            $shippingUrl = str_replace("@prod", $productID, $this->baseShippingUrl);
            return $shippingUrl;
        }
        return null;
    }

    private function getCompanyID($html)
    {
        if ($html) {
            $companyID = $this->useRegExpression($html, '/window.runParams.companyId="(\d+)"/');
            return $companyID;
        }
        return null;
    }

    private function getOwnerMemberID($html)
    {
        if ($html) {
            $ownerMemberID = $this->useRegExpression($html, '/window.runParams.adminSeq="(\d+)"/');
            return $ownerMemberID;
        }
        return null;
    }

    private function getProductID($html)
    {
        if ($html) {
            $productID = $this->useRegExpression($html, '/window.runParams.productId="(.+)"/');
            return $productID;
        }
        return null;
    }

    private function getShipping($url)
    {
        if ($url) {
            $html = file_get_contents($url);
            $shipping = $this->useRegExpression($html, '/\((.+)\)/');
            //dd(json_decode($shipping));
            return json_decode($shipping);
        }
        return null;
    }

    private function getCharacteristics($crawler)
    {
        if ($crawler) {
            $listNodes = $crawler->filter("ul.product-property-list");
            $results = [];
            try {
                foreach ($listNodes->children() as $node) {
                    $crawlerNode = new Crawler($node);
                    $results[] = $this->getCharacteristic($crawlerNode);
                }
            } catch (\Exception $ex) {
                return response()->view('errors.404', [], 404);
            }
            return $results;
        }
        return null;
    }

    /**
     * @param $crawler Crawler
     * @return mixed
     */
    private function getCharacteristicsBlock($crawler)
    {
        if ($crawler) {
            $characteristics = $crawler->filter("ul.product-property-list")->parents()->first()->html();
            return $characteristics;
        }
        return null;
    }

    private function getMainImages($html)
    {
        if ($html) {
            $mainImages = $this->useRegExpression($html, '/window.runParams.imageBigViewURL=\[(.+)\];/s');
            $mainImages = explode(',', $mainImages);
            for ($i = 0; $i < count($mainImages); $i++) {
                $mainImages[$i] = str_replace('"', '', trim($mainImages[$i]));
            }
            return $mainImages;
        }
        return null;
    }

    private function getCharacteristic($crawler)
    {
        if ($crawler) {
            return [[$this->checkResultParser($crawler->filter("li span.propery-title"))],
                [$this->checkResultParser($crawler->filter("li span.propery-des"))]];
        }
        return null;
    }

    private function getOldPrice($crawler)
    {
        if ($crawler) {
            $arr = explode("-", $this->checkResultParser($crawler->filter("del.p-del-price-content span.p-price")));
            foreach ($arr as $key => $value) $arr[$key] = trim($value);
            return $arr;
        }
        return null;
    }

    private function getSalePrice($html)
    {
        if ($html) {
            $saleprice = $this->useRegExpression($html, '/window.runParams.minPromPrice="(.+)"/');
            return $saleprice;
        }
        return null;
    }

    private function getMainPrice($crawler)
    {
        if ($crawler) {
            $price = $this->checkResultParser($crawler->filter("#j-sku-price"));
            $discountPrice = $this->checkResultParser($crawler->filter("#j-sku-discount-price"));
            $discountPrice = strip_tags($discountPrice);

            if ($discountPrice != "" && $discountPrice != null) {
                $arr = explode("-", $discountPrice);
            } else {
                if (is_numeric($price) && is_numeric($discountPrice)) {
                    $mainPrice = min([$price, $discountPrice]);
                } else if ($price) {
                    $mainPrice = $price;
                } else {
                    $mainPrice = $discountPrice;
                }
                $arr = explode("-", $mainPrice);//"span[itemprop=\"price\"]"));
            }
            foreach ($arr as $key => $value) $arr[$key] = trim(strip_tags($value));
            return $arr;
        }
        return null;
    }

    private function getRantingsNum($crawler)
    {
        if ($crawler) {
            return $this->checkResultParser($crawler->filter(".rantings-num"));
        }
        return null;
    }

    private function getPercentNum($crawler)
    {
        if ($crawler) {
            return $this->checkResultParser($crawler->filter(".percent-num"));
        }
        return null;
    }

    private function getProductName($crawler)
    {
        if ($crawler) {
            return $this->checkResultParser($crawler->filter(".product-name"));
        }
        return null;
    }

    private function checkResultParser($crawler, $attr = null)
    {
        if (!$attr && $crawler->count()) {
            return $crawler->html();
        } else if ($attr && $crawler->count()) {
            return $crawler->attr($attr);
        } else {
            return null;
        }
    }

    public function getListSimilarProducts($productID)
    {
        if ($productID) {
            $url = $this->getUrlToAPI("listSimilarProducts");
            $apicall = $url . "productId=" . $productID;
            $response = $this->get_web_page($apicall);
            return $response['content'];
        }
        return null;
    }

    public function getCategoryProducts($categoryID, $pageSize, $currency, $lang = "he", $options = [])
    {
        if ($categoryID) {
            $url = $this->getUrlToAPI("listPromotionProduct");
            $fields = "productId,productTitle,productUrl,imageUrl,originalPrice,salePrice,discount," .
                "evaluateScore,30daysCommission,productTitle,validTime";
            if ($categoryID != -1) {
                $url .= "categoryId=" . $categoryID;
            }

            $apicall = $url
                . "&fields=" . $fields
                . "&pageSize=" . $pageSize
                . "&highQualityItems=true"
                
                . "&language=$lang";

// add optional parameters to category request
            foreach ($options as $key => $option) {
                if ($option != null) {
                    $apicall .= "&$key=$option";
                }
            }
//            dd($apicall);
//            $client = new \GuzzleHttp\Client();
//            $res = $client->request('GET', $apicall);
            $response = $this->get_web_page($apicall);
            //dd($this->get_web_page($apicall));
            return $response['content'];
            //return $this->makeCurlCall($apicall);
        }
        return null;
    }

    public function getSingleProduct($productId, $lang = "he")
    {
        if ($productId) {
            $url = $this->getUrlToAPI("getPromotionProductDetail");
            $fields = "productId,productTitle,productUrl,imageUrl,originalPrice,salePrice,discount," .
                "productTitle,allImageUrls";
            $apicall = $url . "fields=$fields&productId=$productId&language=$lang";

            $response = $this->get_web_page($apicall);
            return $response['content'];
        }
        return null;
    }

    public function getCategoryID($crawler)
    {
        $crawler = $crawler->filter(".ui-breadcrumb div.container h2");
        $url = $crawler->children()->attr("href");
        $categoryID = $this->useRegExpression($url, "/\/([0-9]+)\//");
        return $categoryID;
    }

    public function getShopName($crawler)
    {
        return $this->checkResultParser($crawler->filter(".shop-name a"));
    }

    public function translateCharacteristics($itemSpecifics)
    {
        $itemSpecificsEnglish = [];
        $stringSpecHeb = "";
        foreach ($itemSpecifics as $specific) {
            $stringSpecHeb .= $specific[0][0] . ' . ';
            $stringSpecHeb .= $specific[1][0] . ' ! ';
        }
        $stringSpecEng = Util::translateHebrewToEnglish($stringSpecHeb);
        $arrayEnglish = explode("!", $stringSpecEng);
        foreach ($arrayEnglish as $item) {
            $itemArray = explode(".", $item);
            $english = new \stdClass();
            if (isset($itemArray[0])) {
                $english->value = $itemArray[0];
            } else $english->value = "";
            if (isset($itemArray[1])) {
                $english->name = $itemArray[1];
            } else  $english->name = "";
            $itemSpecificsEnglish[] = $english;
        }
        if ($itemSpecificsEnglish[count($itemSpecificsEnglish) - 1]->value == "") {
            unset($itemSpecificsEnglish[count($itemSpecificsEnglish) - 1]);
        }
        return $itemSpecificsEnglish;
    }

    public function getTotalProductData($getAddImages = true)
    {
//        $this->html = $this->get_web_page($this->productUrl)['content'];

        $html = $this->html;

        $crawler = new Crawler();
        $crawler->addHtmlContent($html, ' UTF-8');
        $productName = $this->getProductName($crawler);
        $percentNum = $this->getPercentNum($crawler);
        $rantingsNum = $this->getRantingsNum($crawler);
        $mainPrice = $this->getMainPrice($crawler);
        $salePrice = $this->getSalePrice($html);
        $oldPrice = $this->getOldPrice($crawler);
        $mainImages = $this->getMainImages($html);
        $characteristics = $this->getCharacteristics($crawler);
        $characteristicsBlock = $this->getCharacteristicsBlock($crawler);
        $shipping = $this->getShipping($this->getShippingUrl($html));
        $feedback = $this->getFeedbackUrl($html);
        $productsSKU = $this->getProductSKU($crawler);
        $orderNum = $this->getOrderNum($crawler);
        $skuArray = $this->getArraySkuProducts($html);
        $sellerInfo = $this->getSellerInfo($html);
        $categoryID = $this->getCategoryID($crawler);
        $shopName = $this->getShopName($crawler);
        $engCharacteristics = $this->translateCharacteristics($characteristics);

        //dd($characteristics);
//dd($categoryID);
//dd($mainPrice);
        $resultArray = [
            'productName' => $productName,
            'percentNum' => $percentNum,
            'rantingsNum' => $rantingsNum,
            'orderNum' => $orderNum,
            'mainPrice' => $mainPrice,
            'salePrice' => $salePrice,
            'oldPrice' => $oldPrice,
            'productSKU' => $productsSKU,
            'skuArray' => $skuArray,
            'mainImages' => $mainImages,
            'characteristics' => $characteristics,
            'characteristicsBlock' => $characteristicsBlock,
            'shipping' => $shipping,
            'feedback' => $feedback,
            'sellerInfo' => $sellerInfo,
            'categoryID' => $categoryID,
            'shopName' => $shopName,
            'engCharacteristics' => $engCharacteristics
        ];

// based on 2nd argument, this method adds additional images to array or not
        if ($getAddImages) {
            $resultArray['additionalImages'] = $this->getAdditionalImages();
        }

        return $resultArray;
    }

    public function getAdditionalImages()
    {
        $html = $this->html;
        $productID = $this->getProductID($html);
        $url = $this->baseDescriptionUrl . $productID;
        $html = file_get_contents($url);

        $description = $this->useRegExpression($html, '/window.productDescription=\'(.+)\';/');
        $description = preg_replace('/href=\".+?\"/s', " ", $description);
        $description = preg_replace('/target=\"\_blank\"/s', " ", $description);
        return $description;
    }

    public function getHotProducts()
    {
        $url = $this->getUrlToAPI("listHotProducts");
        return $this->makeCurlCall($url);
    }
}
