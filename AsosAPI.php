<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 23.08.2017
 * Time: 11:03
 */

namespace App\Http\API;

use App\Util;
use JonnyW\PhantomJs\Client;
use Symfony\Component\DomCrawler\Crawler;

class AsosAPI
{
    private $siteDomain;

    const ITEM_PER_PAGE = 36;

    public function __construct()
    {
        $this->siteDomain = "http://us.asos.com";
    }

    private function getContentFromPhantom($url)
    {
//        $proxy = "69.39.224.128";
//        $port = "80";
//        $username = "chipi1";
//        $password = "kSUnNtJ_cE";
        $client = Client::getInstance();
        $client->isLazy();
//        $client->getEngine()->addOption("--proxy=$proxy:$port");
//        $client->getEngine()->addOption("--proxy-auth=$username:$password");
        $delay = 1; // 1 seconds
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
        return $response->getContent();
    }

    public function getContent($url)
    {
        return $this->getContentFromPhantom($url);
    }

    private function getCountProduct($crawler)
    {
        $isComplexProduct = $crawler->filter('#mix-and-match')->count();
        if ($isComplexProduct == 0) {
            return 1;
        } else {
            $count = $crawler->filter('#mix-and-match > ul > li')->count();
            $countProducts = $count - 5;//5 it is count of items in layout for mobile
        }
        return $countProducts;
    }

    public function getProductsByKeywords($keyword, $page, $sort = '')
    {
        $urlForSearch = "$this->siteDomain/search/$keyword?q=$keyword&pge=" . ($page - 1) . "&pgesize=" . self::ITEM_PER_PAGE
            . "&sort=$sort";
        $data = $this->getProducts($urlForSearch);
        return $data;
    }

    public function getProductsByCategory($categoryId, $page, $sort = '')
    {
        $categoryName = \DB::table('asosSubSubcats')->where("asosCategoryId", $categoryId)->get()->first()->asosUrl;
        $url = "$this->siteDomain/$categoryName?cid=$categoryId&pge=" . ($page - 1) . "&pgesize=" . self::ITEM_PER_PAGE
            . "&sort=$sort";
        $data = $this->getProducts($url);
        return $data;
    }

    private function getProducts($url)
    {
        $client = new \GuzzleHttp\Client();
        $guzzle = $client->request('GET', $url);
        $html = $guzzle->getBody();
        $crawler = new Crawler();
        $crawler->addHtmlContent($html, 'UTF-8');
        $data['products'] = $this->parseProducts($crawler);
        $data['totalEntries'] = str_replace(',', '', $this->checkResultParser($crawler->filter('.total-results')));
        $data['totalPages'] = ceil($data['totalEntries'] / self::ITEM_PER_PAGE);
        return $data;
    }

    private function parseProducts($crawler)
    {
        $blockWithProducts = $crawler->filter(".results ul");
        $products = [];
        foreach ($blockWithProducts->children() as $blockProduct) {
            $crawlerNode = new Crawler($blockProduct);
            $product['productId'] = $crawlerNode->attr("data-productid");
            $product['imageUrl'] = $this->checkResultParser($crawlerNode->filter("a.product div.img-wrap img.product-img"),
                "src");
            $product['title'] = $this->checkResultParser($crawlerNode->filter("a.product div.name-fade span.name"));
            $product['price'] = Util::replacePrices($this->checkResultParser($crawlerNode
                ->filter("div.scm-pricelist div.price-current span.price")), "USR");
            $products[] = $product;
        }
        return $products;
    }

    public function getTotalProductData($url)
    {
        $html = $this->getContent($url);
        $crawler = new Crawler();
        $crawler->addHtmlContent($html, 'UTF-8');
        $countProducts = $this->getCountProduct($crawler);
        $title = $this->getTitle($crawler);
        $images = $this->getImages($crawler);
        $resultArray['title'] = $title;
        $resultArray['images'] = $images;
        $resultArray['productUrl'] = $url;
        if ($countProducts == 1) {
            $currentPrice = $this->getCurrentPrice($crawler);
            $colour = $this->getColour($crawler);
            $size = $this->getSize($crawler);
            $previousPrice = $this->getPreviousPrice($crawler);
            $productDetails = $this->getProductDetails($crawler);
            $productCode = $this->getProductCode($crawler);
            $brandDescription = $this->getBrandDescription($crawler);
            $sizeFit = $this->getSizeFit($crawler);
            $aboutMe = $this->getAboutMe($crawler);
            $resultArray['products'][] = [
                'currentPrice' => Util::replacePrices($currentPrice, "USD"),
                'colour' => $colour,
                'size' => $size,
                'previousPrice' => Util::replacePrices($previousPrice, "USD"),
            ];
            $stringSpecEng = "";
            foreach ($productDetails as $detail) {
                $stringSpecEng .= $detail . ' . ';
            }
            $stringSpecHebrew = Util::translateEnglishToHebrew($stringSpecEng);
            //dd($stringSpecEng);
            $arrayHebrew = explode(".", $stringSpecHebrew);
            $resultArray['productDetailsHebrew'] = $arrayHebrew;
            $resultArray['productDetails'] = $productDetails;
            $resultArray['productCode'] = $productCode;
            $stringSpecEng = $brandDescription . "@ @" . $sizeFit . "@ @" . $aboutMe;
            $stringSpecHebrew = Util::translateEnglishToHebrew($stringSpecEng);
            $arrayHebrew = explode("@ @", $stringSpecHebrew);
            $resultArray['brandDescription'] = $arrayHebrew[0];
            $resultArray['sizeFit'] = $arrayHebrew[1];
            $resultArray['aboutMe'] = $arrayHebrew[2];
        } else {
            $resultArray['products'] = $this->getComplexProduct($crawler, $countProducts);
        }
        return $resultArray;
    }

    private function getComplexProduct($crawler, $countProducts)
    {
        $products = [];
        for ($i = 1; $i <= $countProducts; $i++) {
            $products[] = $this->getPartProduct($crawler->filter("#mix-and-match > ul > li:nth-child($i)"));
        }
        return $products;
    }

    private function getPartProduct($crawler)
    {
        $product['title'] = $crawler->filter(".product-title")->text();
        $product['currentPrice'] = Util::replacePrices($crawler->filter(".current-price")->text(), "USD");
        $product['size'] = $crawler->filter("div.product-info > div > section > div > div > div > select option")
            ->each(function (Crawler $node) {
                return $node->text();
            });
        $product['colour'] = $crawler
            ->filter("div:nth-child(3) > section > div > div.size-section > div.colour-size-select > select option")
            ->each(function (Crawler $node) {
                return $node->text();
            });
        return $product;
    }

    private function getAboutMe($crawler)
    {
        if ($crawler) {
            return $this->checkResultParser($crawler->filter("div.about-me span"));
        }
        return null;
    }

    private function getSizeFit($crawler)
    {
        if ($crawler) {
            return $this->checkResultParser($crawler->filter("div.size-and-fit span"));
        }
        return null;
    }

    private function getBrandDescription($crawler)
    {
        if ($crawler) {
            return $crawler->filter("div.brand-description span")->text();
        }
        return null;
    }

    private function getProductCode($crawler)
    {
        if ($crawler) {
            return $this->checkResultParser($crawler->filter("div.product-code span"));
        }
        return null;
    }

    private function getProductDetails($crawler)
    {
        if ($crawler) {
            return $crawler->filter("div.product-description span ul li")->each(function (Crawler $node) {
                return $node->text();
            });
        }
        return null;
    }

    private function getTitle($crawler)
    {
        if ($crawler) {
            return $this->checkResultParser($crawler->filter("div.product-hero h1"));
        }
        return null;
    }

    private function getCurrentPrice($crawler)
    {
        if ($crawler) {
            return $this->checkResultParser($crawler->filter("#product-price div span.current-price"));
        }
        return null;
    }

    private function getPreviousPrice($crawler)
    {
        if ($crawler) {
            return $this->checkResultParser($crawler->filter("#product-price > div.grid-row.rendered > span:nth-child(4)"));
        }
        return null;
    }

    private function getColour($crawler)
    {
        if ($crawler) {
            return $crawler->filter('#product-colour > section > div > div > div > select option')->each(function (Crawler $node) {
                return $node->text();
            });
        }
        return null;
    }

    private function getImages($crawler)
    {
        if ($crawler) {
            return $crawler->filter('.gallery-image')->each(function (Crawler $node) {
                return $node->attr('src');
            });
        }
        return null;
    }

    private function getSize($crawler)
    {
        if ($crawler) {
            return $crawler->filter('#product-size > section > div > div.size-section > div.colour-size-select > select option')
                ->each(function (Crawler $node) {
                    return $node->text();
                });
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
}