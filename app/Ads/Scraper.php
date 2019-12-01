<?php


namespace App\Services;

use KubAT\PhpSimple\HtmlDomParser;

class Scraper
{
    public function scrapeVivaStreet(string $link): void
    {
        $selector = 'div[class=clad]';
        set_time_limit(0);
        $curlResponse = CurlExecService::curlExec($link);
        $MainPageHtml = HtmlDomParser::str_get_html($curlResponse);
        $listOfCarsHtml = $MainPageHtml->find($selector);

        foreach ($listOfCarsHtml as $index => $listItemHtml){
            new Advertisement($listItemHtml);
        }
    }
}
