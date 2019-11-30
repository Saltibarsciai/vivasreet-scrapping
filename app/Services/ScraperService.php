<?php


namespace App\Services;


use KubAT\PhpSimple\HtmlDomParser;

class ScraperService
{
    /**
     * @param string $link
     */
    public function scrapeVivaStreet(string $link): void
    {
        $curlResponse = CurlExecService::curlExec($link);
        $MainPageHtml = HtmlDomParser::str_get_html($curlResponse);
        $listOfCarsHtml = $MainPageHtml->find('div[class=clad]');
        $this->iterateOverEveryResult($listOfCarsHtml);
    }
    private function iterateOverEveryResult($listOfCarsHtml){
        foreach ($listOfCarsHtml as $index => $listItemHtml){
            $ad = new Advertisement($listItemHtml);
        }
    }
}
