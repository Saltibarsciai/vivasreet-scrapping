<?php


namespace App\Services;


use KubAT\PhpSimple\HtmlDomParser;

class Advertisement
{
    const DEFAULT_STR = 'Not provided';
    const DEFAULT_NUMBER = -1;
    public $title = self::DEFAULT_STR;
    public $description = self::DEFAULT_STR;
    public $price = self::DEFAULT_NUMBER;
    public $year = self::DEFAULT_NUMBER;
    public $mileage = self::DEFAULT_NUMBER;
    public $phone = self::DEFAULT_NUMBER;
    public $id = self::DEFAULT_NUMBER;
    public $linkToCarPage = self::DEFAULT_STR;

    public function __construct($carHtml)
    {
        $this->linkToCarPage = $carHtml->children(0)->href;
        $carPageResponse = CurlExecService::curlExec($this->linkToCarPage);
        $carPageHtml = HtmlDomParser::str_get_html($carPageResponse);
        $this->title = $carPageHtml->find("h1[class=kiwii-font-xlarge kiwii-margin-none kiwii-font-weight-semibold]", 0)->plaintext;
        $this->description = $carPageHtml->find("div[class=shortdescription font-panel-content]", 0)->plaintext;
        $fieldWithId = $carPageHtml->find("ul[class=kiwii-description-footer]", 0)->children(0)->plaintext;
        $modifiedFieldWithId = str_replace("Ad ID", "", $fieldWithId);
        $this->id = str_replace(" ", "", $modifiedFieldWithId);
        if(isset($carPageHtml->find("span[class=phone_link]", 0)->id)){
            $this->phone = $carPageHtml->find("span[class=phone_link]", 0)->{'data-phone-number'};
        }
        $this->price = $carPageHtml->find("td[class=kiwii-font-weight-semibold kiwii-font-primary kiwii-font-default kiwii-padding-top-xxxsmall]", 0)->plaintext;
        $this->year = $carPageHtml->find("table[id=details-tbl-specs]", 0)->children(4)->children(1)->plaintext;
        $this->mileage = $carPageHtml->find("table[id=details-tbl-specs]", 0)->children(6)->children(1)->plaintext;

        if(isset($carPageHtml->find("img[id=vs_photos_img_0]", 0)->src)){
            $imageSource = $carPageHtml->find("img[id=vs_photos_img_0]", 0)->src;
            $imageId= $carPageHtml->find("img[id=vs_photos_img_0]", 0)->id;
        }
        if(isset($carPageHtml->find("img[id=vs_photos_img_1]", 0)->{'data-src'})){
            $imageSource = $carPageHtml->find("img[id=vs_photos_img_1]", 0)->{'data-src'};
            $imageId= $carPageHtml->find("img[id=vs_photos_img_1]", 0)->id;
        }
        if(isset($carPageHtml->find("img[id=vs_photos_img_2]", 0)->{'data-src'})){
            $imageSource = $carPageHtml->find("img[id=vs_photos_img_2]", 0)->{'data-src'};
            $imageId= $carPageHtml->find("img[id=vs_photos_img_2]", 0)->id;
        }
        if(isset($carPageHtml->find("img[id=vs_photos_img_3]", 0)->{'data-src'})){
            $imageSource = $carPageHtml->find("img[id=vs_photos_img_3]", 0)->{'data-src'};
            $imageId= $carPageHtml->find("img[id=vs_photos_img_3]", 0)->id;
        }
//        curl_setopt($ch, CURLOPT_URL, $carPageDom->find("img[id=vs_photos_img_0]", 0)->src);
//        $data = curl_exec($ch);
//        Storage::disk('public')->put("{$id}/{$imgId1}.jpg", $data);
//        dd($imgId1);
//        echo $carPage;
//        dd($carPageDom->find("img[id=vs_photos_img_0]", 0));
    }
}
