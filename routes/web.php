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

use App\Services\CurlExecService;
use Illuminate\Support\Facades\Storage;
use KubAT\PhpSimple\HtmlDomParser;

Route::get('/', function () {
    $html = CurlExecService::curlExec('https://search.vivastreet.co.uk/cars/gb');
    $dom = HtmlDomParser::str_get_html( $html);
    $elements = $dom->find('div[class=clad]');
    foreach ($elements as $index => $element){
        $link =  $element->children(0)->href;
        echo $link;
        $carPage = CurlExecService::curlExec($link);
        $carPageDom = HtmlDomParser::str_get_html( $carPage);
        echo $carPageDom->find("h1[class=kiwii-font-xlarge kiwii-margin-none kiwii-font-weight-semibold]", 0)->plaintext."<br/>";
        echo $carPageDom->find("div[class=shortdescription font-panel-content]", 0)->plaintext."<br/>";
        $id = str_replace("Ad ID", "", $carPageDom->find("ul[class=kiwii-description-footer]", 0)->children(0)->plaintext);
        $id = str_replace(" ", "", $id);
        echo $id."<br/>" ;
        if(isset($carPageDom->find("span[class=phone_link]", 0)->id)){
            $phone_number = $carPageDom->find("span[class=phone_link]", 0)->{'data-phone-number'};
            echo $phone_number."<br/>";
        }
        echo $carPageDom->find("td[class=kiwii-font-weight-semibold kiwii-font-primary kiwii-font-default kiwii-padding-top-xxxsmall]", 0)->plaintext."<br/>";
        echo $carPageDom->find("table[id=details-tbl-specs]", 0)->children(4)->children(1)->plaintext."<br/>";
        echo $carPageDom->find("table[id=details-tbl-specs]", 0)->children(6)->children(1)->plaintext."<br/>";
        echo "<br/>";
        $imgId1 = "something";
        if(isset($carPageDom->find("img[id=vs_photos_img_0]", 0)->src)){
            echo $carPageDom->find("img[id=vs_photos_img_0]", 0)->src."<br/>";
            $imgId1 = $carPageDom->find("img[id=vs_photos_img_0]", 0)->id;
//            echo $imgId1;
        }
        if(isset($carPageDom->find("img[id=vs_photos_img_1]", 0)->{'data-src'})) echo $carPageDom->find("img[id=vs_photos_img_1]", 0)->{'data-src'}."<br/>";
        if(isset($carPageDom->find("img[id=vs_photos_img_2]", 0)->{'data-src'})) echo $carPageDom->find("img[id=vs_photos_img_2]", 0)->{'data-src'}."<br/>";
        if(isset($carPageDom->find("img[id=vs_photos_img_3]", 0)->{'data-src'})) echo $carPageDom->find("img[id=vs_photos_img_3]", 0)->{'data-src'}."<br/>";

        echo "<br/>";

//        curl_setopt($ch, CURLOPT_URL, $carPageDom->find("img[id=vs_photos_img_0]", 0)->src);
//        $data = curl_exec($ch);
//        Storage::disk('public')->put("{$id}/{$imgId1}.jpg", $data);
//        dd($imgId1);
//        echo $carPage;
//        dd($carPageDom->find("img[id=vs_photos_img_0]", 0));
    }
//    echo $dom->find("div[class=clad]", 0)->children(0)->href;

//    return view('partials.master')->with('html', $html);
});
Route::resource('cars', 'CarController');
