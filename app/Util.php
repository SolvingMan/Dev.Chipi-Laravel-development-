<?php

namespace App;

use \Stichoza\GoogleTranslate\TranslateClient;

class Util
{
    // convert strings like $10-15$ to string 10 - 15 and $16 to 16
    public static function replacePrices($price, $currency = "USR", $shop = 'ebay')
    {
        if ($price == "") return 0;
        if (!is_array($price)) $price = [$price];

        foreach ($price as $key => $pricePart) {
            $pricePart = self::moneyToFloat($pricePart);

            $price[$key] = self::calculate_main_price($pricePart, $currency, $shop);
        }
        sort($price);
        return implode(" - ", $price);
    }

    // strip money string from all its unneeded symbols
    private static function moneyToFloat($money)
    {
        $cleanString = preg_replace('/([^0-9\.,])/i', '', $money);
        $onlyNumbersString = preg_replace('/([^0-9])/i', '', $money);

        $separatorsCountToBeErased = strlen($cleanString) - strlen($onlyNumbersString) - 1;

        $stringWithCommaOrDot = preg_replace('/([,\.])/', '', $cleanString, $separatorsCountToBeErased);
        $removedThousandSeparator = preg_replace('/(\.|,)(?=[0-9]{3,}$)/', '', $stringWithCommaOrDot);

        return (float)str_replace(',', '.', $removedThousandSeparator);
    }

    public static function getExchangeRate($from, $to)
    {
        if (session("{$from}to{$to}") == null) {
            session(["{$from}to{$to}" => self::convertCurrency(1, $from, $to)]);
        }

        return session("{$from}to{$to}");
    }

    // check if string is hebrew
    public static function is_hebrew($strings)
    {
        $sickHebrewDetectingRegex = "/[\x{0591}-\x{05F4}]/u";
        if (is_array($strings)) {
            foreach ($strings as $string) {
                if (preg_match($sickHebrewDetectingRegex, $string) > 0) {
                    return true;
                };
            }
        } else {
            if (preg_match($sickHebrewDetectingRegex, $strings) > 0) {
                return true;
            };
        }
        return false;
    }

    public static function getLinksForPagination($pagesAvailable, $action, $categoryName, $page, $siteslug, $filterUrl = null,
                                                 $categoryId = null)
    {
        if (isset($pagesAvailable)) {
            $categoryId = isset($categoryId) ? "/" . $categoryId : "";
            foreach ($pagesAvailable as $pageAvailable) {
                $result['pages'][$pageAvailable] = "/" . $siteslug . "/$action/" . $categoryName . $categoryId . "/"
                    . $pageAvailable . $filterUrl;
            }
            if ($page < end($pagesAvailable)) {
                $result['pageNext'] = "/" . $siteslug . "/$action/" . $categoryName . $categoryId . "/" . ($page + 1)
                    . $filterUrl;
            } else {
                $result['pageNext'] = null;
            }
            return $result;
        } else {
            return null;
        }
    }

    public static function getPaginationList($page, $totalPages)
    {
        if ($totalPages < 2) {
            return null;
        }

        // show how much pages
        $totalShown = ($totalPages >= 10) ? 10 : $totalPages;

        // first current and last page ( they will be here 100% time
        $pagesAvailable[] = $totalPages;
        $pagesAvailable[] = $page;
        if ($page != 1) {
            $pagesAvailable[] = 1;
        }
        for ($i = 1; $i < $totalShown - 1;) {
            if (($page + $i) < $totalPages) {
                $pagesAvailable[] = $page + $i;
                if (($page - $i) > 0) {
                    $pagesAvailable[] = $page - $i;
                }
                $i++;
            } else {
                if (($page - $i) > 0) {
                    $pagesAvailable[] = $page - $i;
                    $i++;
                } else {
                    $i++;
                }
            }
            if (($page - $i) > 0) {
                $pagesAvailable[] = $page - $i;
                if (($page + $i) < $totalPages) {
                    $pagesAvailable[] = $page + $i;
                }
                $i++;
            } else {
                if (($page + $i) < $totalPages) {
                    $pagesAvailable[] = $page + $i;
                    $i++;
                }
            }
        }

        sort($pagesAvailable);

        return $pagesAvailable;
    }


    // convert
    static function convertCurrency($amount, $from, $to)
    {
        $url = "https://www.google.com/finance/converter?a=$amount&from=$from&to=$to";
        $data = file_get_contents($url);
        preg_match("/<span class=bld>(.*)<\/span>/", $data, $converted);
        $converted = preg_replace("/[^0-9.]/", "", $converted[1]);
        return round($converted, 3);
    }

    static function objectToArray($data)
    {
        if (is_array($data) || is_object($data)) {
            $result = array();

            foreach ($data as $key => $value) {
                $result[$key] = self::objectToArray($value);
            }

            return $result;
        }
        return $data;
    }

    static function includeJsWithData($objectName, $keyVals)
    {
        $scriptText = "<script>var " . $objectName . "=" . json_encode($keyVals) . "</script>";

        return $scriptText;
    }

    static function translateHebrewToEnglishFromDatabase($searchKeywordHebrew)
    {
        $keywordEnglish = \DB::table('search')->where('hebrew', 'like', $searchKeywordHebrew)
            ->select("english")->first();
        $keywordEnglish = isset($keywordEnglish->english) ? $keywordEnglish->english : null;
        //dd($keywordEnglish);
        if (!$keywordEnglish) {
            $translateClient = new TranslateClient('he', 'en');
            $keywordEnglish = $translateClient->translate($searchKeywordHebrew);
        }
        return $keywordEnglish;
    }

    static function storeSearchRequest($english, $site, $hebrewWord = '-')
    {
        \DB::insert("insert into search (english,hebrew,site,date) values (?,?,?,?)", [$english,
            $hebrewWord, $site, date("Y-m-d")]);
    }

    static function translateEnglishToHebrew($stringForTranslate)
    {
        $translateClient = new TranslateClient('en', 'he');
        $hebrewString = $translateClient->translate($stringForTranslate);
        return $hebrewString;
    }

    static function translateHebrewToEnglish($stringForTranslate)
    {
        $translateClient = new TranslateClient('he', 'en');
        $englishString = $translateClient->translate($stringForTranslate);
        return $englishString;
    }

    static public function calculate_main_price($price, $currency = 'USD', $shop = "ebay")
    {
        $dolar = 3.9;
        $creditCardFee = 1.04;
        $gpbCurrency = 5.6;
        $euro = 4.5;
        if ($currency == "GBP") {
            $productprice = $price * $gpbCurrency;
        } elseif ($currency == "EUR") {
            $productprice = $price * $euro;
        } elseif ($currency == "ILS") {
            $productprice = $price;
        } else {
            $productprice = $price * $dolar;
        }

        $sum = ($productprice * $creditCardFee);
        if ($shop == "next") {
            $profit = 1.1;
        } else {
            if ($sum <= 50) $profit = 1.4;
            elseif ($sum <= 150) $profit = 1.25;
            elseif ($sum <= 250) $profit = 1.2;
            elseif ($sum <= 450) $profit = 1.15;
            elseif ($sum <= 800) $profit = 1.12;
            else $profit = 1.1;
        }
        $sumAll = $sum * $profit;

        return ceil($sumAll);
    }

    static public function calculate_shipping_price($price, $currency = 'USD')
    {
        $dolar = 3.9;
        $creditCardFee = 1.04;
        $gpbCurrency = 5.6;
        $euro = 4.5;
        if ($currency == "GBP") {
            $productprice = $price * $gpbCurrency;
        } elseif ($currency == "EUR") {
            $productprice = $price * $euro;
        } else {
            $productprice = $price * $dolar;
        }

        $sum = ($productprice * $creditCardFee);

//         if ($sum <= 50) $profit = 1.40;
//         elseif ($sum <= 150) $profit = 1.25;
//         elseif ($sum <= 400) $profit = 1.20;
//         elseif ($sum <= 800) $profit = 1.15;
//         else $profit = 1.10;
        $profit = 1.1;

        $sumAll = $sum * $profit;

        return ceil($sumAll);
    }
//    static public function calculate_price($price)
//    {
//        $dolar = 3.8;
//        $creditCardFee = 1.04;
//        $gpbCurrency = 5.6;
//        $productprice = $price * $dolar;

//        $sum = ($productprice * $creditCardFee);

//        if ($sum <= 50) $profit = 1.40;
//        elseif ($sum <= 150) $profit = 1.25;
//        elseif ($sum <= 400) $profit = 1.20;
//        elseif ($sum <= 800) $profit = 1.15;
//        else $profit = 1.10;

//        $sumAll = $sum * $profit;

//        return ceil($sumAll);
//    }

    static function sendUserToNewsLater($email, $name)
    {
        $req = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?> <InfoMailClient> <UpdateContacts> <User> <Username>chipiisrael217" .
            "</Username> <Token>652984760b078</Token> </User> <Contacts handleEvents=\"true\"> <Contact fname=\"$name\" email=\"$email\"" .
            "addToGroupName=\"chipi\"/> </Contacts> </UpdateContacts> </InfoMailClient>";
        $url = "http://infomail.inforu.co.il/api.php?xml=";
        $post_data = array("xml" => $req,);
        $stream_options = array('http' => array('method' => 'POST', 'header' =>
            "Content-type: application/x-www-form-urlencoded\r\n", 'content' => http_build_query($post_data),),);
        $context = stream_context_create($stream_options);
        $response = file_get_contents($url, null, $context);
    }

    public static function convertDate($date, $targetFormat)
    {
        $date = new \DateTime($date);

        return $date->format($targetFormat);
    }

    static public function checkHTTPS($link)
    {
        $link_array = explode(':', $link);
        if (stripos($link_array[0], 's') !== false) {
            $link_array = implode(':', $link_array);
        } else {
            $link_array = $link_array[0] . 's' . ':' . $link_array[1];
        }

        return $link_array;
    }

    static public function chooseCurrentTabAndTimer()
    {
        $currentHours = date("H");
        if ($currentHours >= 10 && $currentHours < 16) {
            $tab = 1;
            $timerTime = date("Y/m/d 16:00:00");
        } else if ($currentHours >= 16 && $currentHours < 22) {
            $tab = 2;
            $timerTime = date("Y/m/d 22:00:00");
        } else if ($currentHours >= 22 && $currentHours > 4) {
            $tab = 3;
            $timerTime = date("Y/m/d 04:00:00");
        } else {
            $tab = 4;
            $timerTime = date("Y/m/d 10:00:00");
        }
        $data["tab"] = $tab;
        $data["timerTime"] = $timerTime;
        return $data;
        //dd($currentTime);
    }
}