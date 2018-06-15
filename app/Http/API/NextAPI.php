<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 25.07.2017
 * Time: 12:02
 */

namespace App\Http\API;

use App\Util;
use JonnyW\PhantomJs\Client;
use Symfony\Component\DomCrawler\Crawler;


class NextAPI
{
    //private $itemPerPage;

    const ITEM_PER_PAGE = 24;

    function __construct()
    {
    }

    private function getContentFromPhantom($url)
    {
        $client = Client::getInstance();
        $client->getProcedureCompiler()->enableCache();
        $delay = 2; // 2 seconds
        $request = $client->getMessageFactory()->createRequest($url, 'GET');
        $request->setDelay($delay);
        $response = $client->getMessageFactory()->createResponse();
        if (env('APP_ENV') == \Config::get('enums.env.LIVE') ||
            env('APP_ENV') == \Config::get('enums.env.DEV')) {
            $client->getEngine()->setPath('/home/chipi/dev.chipi.co.il/bin/phantomjs');
        } else {
            $client->getEngine()->setPath('C:\OpenServer\domains\chipi.dev\bin\phantomjs.exe');
        }


        // Send the request
        $client->send($request, $response);
        //if ($response->getStatus() === 200 || $response->getStatus() === 301) {
        //dd($response->getContent());
        return $response->getContent();
        // }
        //return null;
    }

    public function getContent($url)
    {
        return $this->getContentFromPhantom($url);
    }

    private function getCountProducts($crawler)
    {
        return floatval($this->checkResultParser($crawler->filter(".ResultCount div.Count")));
    }

    public function saveProductsByCategoriesInDB($categories)
    {
        \DB::table('nextProductsByCategory')->truncate();
        foreach ($categories as $category) {
            $fullUrl = "http://www.next.co.il/he/shop/" . $category->nextUrl;
            $crawler = $this->getCrawlerInstance($fullUrl);
            $countProducts = $this->getCountProducts($crawler);
            $countPages = $this->getCountPages($countProducts);
            for ($i = 1; $i <= $countPages; $i++) {
                $this->saveProductsByCategoryInDB($fullUrl, $i, $category->subSubcatId, $countProducts);
            }
        }
    }

    private function getCrawlerInstance($url)
    {
        $html = $this->getContent($url);
        $crawler = new Crawler();
        $crawler->addHtmlContent($html, 'UTF-8');
        return $crawler;
    }

    private function saveProductsByCategoryInDB($url, $pageNum, $categoryId, $countProducts)
    {
        $fullUrl = $url . '#' . $pageNum . '_172';
        $crawler = $this->getCrawlerInstance($fullUrl);
        $firstItemNum = (($pageNum - 1) * self::ITEM_PER_PAGE) + 1;
        $lastItemNum = ($pageNum * self::ITEM_PER_PAGE) > $countProducts ? $countProducts : ($pageNum * self::ITEM_PER_PAGE);
        for ($i = $firstItemNum; $i <= $lastItemNum; $i++) {
            $title = $this->checkResultParser($crawler->filter("#i" . $i . " section div.Info h2.Title a"));
            $price = $this->checkResultParser($crawler->filter("#i" . $i . " section div.Info div.Price a"));
            $price = str_replace('₪ ', '', $price);
            $imageUrl = $this->checkResultParser($crawler->filter("#i" . $i . " section div.Images a img"), "src");
            $productUrl = $this->checkResultParser($crawler->filter("#i" . $i . " section div.Info h2.Title a"), "href");
            if ($title && $price && $imageUrl) {
                \DB::table('nextProductsByCategory')->insert(['categoryID' => $categoryId, 'title' =>
                    trim(strip_tags($title)), 'price' => trim($price), 'imageUrl' => $imageUrl, 'productUrl' => $productUrl]);
            }
        }
    }

    private function getItemNumber($crawler)
    {
        if ($crawler) {
            return $this->checkResultParser($crawler->filter("div.ItemNumber")->first());
        }
        return null;
    }

    private function getDescription($crawler)
    {
        if ($crawler) {
            return $crawler->filter("#Composition")->text();
        }
        return null;
    }

    private function getHdnPage($crawler)
    {
        if ($crawler) {
            return $this->checkResultParser($crawler->filter("input#hdnPage")->first(), 'value');
        }
        return null;
    }

    private function getHdnPublication($crawler)
    {
        if ($crawler) {
            return $this->checkResultParser($crawler->filter("input#hdnPublication")->first(), 'value');
        }
        return null;
    }

    private function getTitileProduct($crawler)
    {
        if ($crawler) {
            return $this->checkResultParser($crawler->filter("div.Title h1")->first());
        }
        return null;
    }

    private function getMainImages($crawler)
    {
        if ($crawler) {
            return $this->checkResultParser($crawler->filter("div.ShotView img")->first(), 'src');
        }
        return null;
    }

    private function getJsonData($crawler)
    {
        if ($crawler) {
            $jsonData = $this->checkResultParser($crawler->filter("section.ProductDetail script"));
            $rep = array('var shotData=', ';');
            $to = array('', '');
            $json = str_replace($rep, $to, $jsonData);
            return json_decode($json, true);
        }
        return null;
    }

    private function getAdditionalImages($crawler)
    {
        $hdnPage = $this->getHdnPage($crawler);
        $hdnPublication = $this->getHdnPublication($crawler);
        $jsonData = $this->getJsonData($crawler);
        $jsonData['hdnPage'] = $hdnPage;
        $jsonData['hdnPublication'] = $hdnPublication;
        $jsonData['Styles'] = $jsonData['Styles'][0];
        return $jsonData;
    }

    private function getPrice($crawler)
    {
        if ($crawler) {
            $price = $this->checkResultParser($crawler->filter("div.Price span")->first());
            $rep = array('₪', '<!--DNR-->', ' ');
            $to = array('', '', '');
            $newPrice = str_replace($rep, $to, $price);
            return $newPrice;
        }
        return null;
    }

    public function getTotalProductData($url)
    {
        $client = new  \GuzzleHttp\Client();
        $res = $client->request('GET', $url);
        $result = (string)$res->getBody();
        $crawler = new Crawler();
        $crawler->addHtmlContent($result, 'UTF-8');
        $titileProduct = $this->getTitileProduct($crawler);
        $getMainImages = $this->getMainImages($crawler);
        $additionalImages = $this->getAdditionalImages($crawler);
        $price = explode('-', $this->getPrice($crawler));
        $price = Util::replacePrices($price, "ILS", "next");
        $itemNumber = $this->getItemNumber($crawler);
        $description = $this->getDescription($crawler);
        $resultArray = [
            'itemNumber' => $itemNumber,
            'titileProduct' => $titileProduct,
            'getMainImages' => $getMainImages,
            'additionalImagesAndVarilables' => $additionalImages,
            'price' => $price,
            'description' => $description,
        ];
        return $resultArray;
    }

    public function getSearchProducts($pageNum, $keyword, $itemPerPage)
    {
        $fullUrl = 'http://www.next.co.il/he/search?w=' . $keyword . '#' . $pageNum . '_172';
        $crawler = $this->getCrawlerInstance($fullUrl);
        $countProducts = $this->getCountProducts($crawler);
        $countPages = $this->getCountPages($countProducts);
        $result['countPages'] = $countPages;
        $result['countProducts'] = $countProducts;
        $result['products'] = $this->getProductsByKeyword($crawler, $pageNum, $countProducts, $itemPerPage);
        return $result;
    }

    private function getProductsByKeyword($crawler, $pageNum, $countProducts, $itemPerPage)
    {
        $products = [];
        $firstItemNum = (($pageNum - 1) * $itemPerPage) + 1;
        $lastItemNum = ($pageNum * $itemPerPage) > $countProducts ? $countProducts : ($pageNum * $itemPerPage);
        for ($i = $firstItemNum; $i <= $lastItemNum; $i++) {
            $product = new \stdClass();
            $product->title = trim(strip_tags($this->checkResultParser($crawler->filter("#i" . $i . " section div.Info h2.Title a"))));
            $product->price = trim($this->checkResultParser($crawler->filter("#i" . $i . " section div.Info div.Price a")));
            $price = explode('-', $product->price);
            $product->price = Util::replacePrices($price, "ILS", "next");
            $product->imageUrl = $this->checkResultParser($crawler->filter("#i" . $i . " section div.Images a img"), "src");
            $product->productUrl = $this->checkResultParser($crawler->filter("#i" . $i . " section div.Info h2.Title a"), "href");
            $products[] = $product;
        }
        return $products;
    }

    private function getCountPages($countProducts)
    {
        return ceil($countProducts / self::ITEM_PER_PAGE);
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
}