<?php


namespace App\Services;

use App\Car;
use Illuminate\Filesystem\Filesystem;
use KubAT\PhpSimple\HtmlDomParser;

class Scraper
{
    public function scrapeVivaStreet(string $link): void
    {
        set_time_limit(0);
        //Delete previous website data
        Car::query()->delete();
        (new Filesystem())->cleanDirectory('/storage/app/public');

        $curlResponse = CurlExecService::curlExec($link);
        $MainPageHtml = HtmlDomParser::str_get_html($curlResponse);
        $selector = 'div[class=clad]';
        $listOfCarsHtml = $MainPageHtml->find($selector);

        foreach ($listOfCarsHtml as $listItemHtml){
            new Advertisement($listItemHtml);
        }
    }
}
