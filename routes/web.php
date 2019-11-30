<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use KubAT\PhpSimple\HtmlDomParser;

Route::get('/', function () {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://search.vivastreet.co.uk/cars/gb');
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; CrawlBot/1.0.0)');
    curl_setopt($ch, CURLOPT_FAILONERROR, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $html = curl_exec($ch);
//    curl_close($ch);
//    $regex = "|<img class=\"clad__image photo\" src=\"(.*?)\" original=\"(.*?)\"|";
//    preg_match_all($regex, $html, $matches);
//    preg_match_all("|<div class=\"clad\"(.*)|", $html, $results);
//
    $dom = HtmlDomParser::str_get_html( $html);

    $elements = $dom->find('div[class=clad]');
    foreach ($elements as $index => $element){
        $link =  $element->children(0)->href;
        echo $link;
//        echo $dom->find("div[class=clad]", $index)->children(0)->children(0)->children(0)->children(0)->original."<br/>";
//        echo $dom->find("div[class=clad]", $index)->children(0)->children(1)->children(1)->children(0)->plaintext."<br/>";
//        echo $dom->find("div[class=clad]", $index)->children(0)->children(1)->children(1)->children(0)->plaintext."<br/>";
//        echo "<br/>";
        curl_setopt($ch, CURLOPT_URL, $link);
        $carPage = curl_exec($ch);
        $carPageDom = HtmlDomParser::str_get_html( $carPage);
        echo $carPageDom->find("h1[class=kiwii-font-xlarge kiwii-margin-none kiwii-font-weight-semibold]", 0)->plaintext."<br/>";
        echo $carPageDom->find("div[class=shortdescription font-panel-content]", 0)->plaintext."<br/>";
//        $carPageDom->find("ul[class=kiwii-description-footer]", 0)->children(0)->plaintext."<br/>"
        echo str_replace("Ad ID", "", $carPageDom->find("ul[class=kiwii-description-footer]", 0)->children(0)->plaintext)."<br/>" ; //str_replace add id needed
        if(isset($carPageDom->find("span[class=phone_link]", 0)->id)){
            echo $carPageDom->find("span[class=phone_link]", 0)->{'data-phone-number'}."<br/>";
        }
        echo $carPageDom->find("td[class=kiwii-font-weight-semibold kiwii-font-primary kiwii-font-default kiwii-padding-top-xxxsmall]", 0)->plaintext."<br/>";
        echo $carPageDom->find("table[id=details-tbl-specs]", 0)->children(4)->children(1)->plaintext."<br/>";
        echo $carPageDom->find("table[id=details-tbl-specs]", 0)->children(6)->children(1)->plaintext."<br/>";
        echo "<br/>";

//        echo $carPage;
    }
//    echo $dom->find("div[class=clad]", 0)->children(0)->href;

//    return view('partials.master')->with('html', $html);
});
Route::resource('cars', 'CarController');
