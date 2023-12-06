<?php

require 'vendor/autoload.php';

use Goutte\Client;

class WebScrapper{

    function getOlxListingPrice($url) {
        $client = new Client();
        $crawler = $client->request('GET', $url);
    
        $priceElement = $crawler->filter('.css-12vqlj3');
    
        if ($priceElement->count() > 0) {
            $priceText = $priceElement->text();
            $priceText = str_replace(' ', '', $priceText); 
            $numericPrice = filter_var($priceText, FILTER_SANITIZE_NUMBER_INT);
            return $numericPrice;
        } else {
            echo "Price element not found.";
            return null;
        }
    }
}