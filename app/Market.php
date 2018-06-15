<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 28.06.2017
 * Time: 12:21
 */

namespace App;


class Market
{
    function getLatestDeals($menFashion, $womenFashion, $electronic, $site = 'ebay')
    {
        $latestDeals = [$menFashion[25], $womenFashion[25], $electronic[25], $menFashion[38], $womenFashion[38], $electronic[38],
            $menFashion[33], $womenFashion[32], $electronic[11], $menFashion[23], $womenFashion[17], $electronic[28]];
        for ($i = 0; $i < count($latestDeals); $i++) {
            $deal = new \stdClass();
            $deal->discount = ceil(mt_rand(20, 50));
            if ($site == 'ebay') {
                $deal->salePrice = $latestDeals[$i]->price;
            } else if ($site == 'ali') {
                $deal->salePrice = $latestDeals[$i]->salePrice;
            }
            $deal->imageUrl = $latestDeals[$i]->imageUrl;
            $deal->productUrl = $latestDeals[$i]->productUrl;
            $deal->productTitle = $latestDeals[$i]->productTitle;
            $deal->productId = $latestDeals[$i]->productId;
            $deal->originalPrice = ceil($deal->salePrice * ($deal->discount / 100 + 1));
            $latestDeals[$i] = $deal;
        }
        return $latestDeals;
    }

    function getRecommendProducts($recommendItems)
    {

        for ($i = 0; $i < count($recommendItems); $i++) {
            $recommendItems[$i]->price = Util::calculate_main_price($recommendItems[$i]->price);
        }

        for ($i = 0; $i < count($recommendItems); $i++) {
            $recommendItems[$i]->discount = ceil(mt_rand(30, 60));
            $recommendItems[$i]->oldPrice = $recommendItems[$i]->price +
                ($recommendItems[$i]->price * ($recommendItems[$i]->discount / 100));
        }

        return $recommendItems;
    }
}