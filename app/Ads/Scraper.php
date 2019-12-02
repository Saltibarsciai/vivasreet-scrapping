<?php


namespace App\Services;

use App\Car;
use Illuminate\Filesystem\Filesystem;
use KubAT\PhpSimple\HtmlDomParser;

class Scraper
{
    /**
     * @param string $link
     */
    public function scrapeVivaStreet(string $link): void
    {
        set_time_limit(0);
        //Keep data, but remove from display
        Car::query()->update(['active' => false]);

        //New website
        $curlResponse = app(CurlExecuteService::class)->curlExecute($link);
        $MainPageHtml = HtmlDomParser::str_get_html($curlResponse);
        $selector = 'div[class=clad]';
        $listOfCarsHtml = $MainPageHtml->find($selector);

        foreach ($listOfCarsHtml as $index => $listItemHtml) {
            if ($index >= 10) {
                break;
            }
            new Advertisement($listItemHtml);
        }
    }
}
