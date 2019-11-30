<?php


namespace App\Services;


use App\Car;
use App\Image;
use Illuminate\Support\Facades\Storage;
use KubAT\PhpSimple\HtmlDomParser;

class Advertisement
{
    const DEFAULT_STR = 'Not provided';
    const DEFAULT_NUMBER = 0;
    public $title = self::DEFAULT_STR;
    public $description = self::DEFAULT_STR;
    public $price = self::DEFAULT_NUMBER;
    public $year = self::DEFAULT_NUMBER;
    public $mileage = self::DEFAULT_NUMBER;
    public $phone = self::DEFAULT_NUMBER;
    public $id = self::DEFAULT_NUMBER;
    public $linkToCarPage = self::DEFAULT_STR;
    public $imageSource = self::DEFAULT_STR;
    public $imageId = self::DEFAULT_NUMBER;

    public $car;

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
        $this->car = $this->createCarRecord();
        $this->dealWithImages($carPageHtml);
    }
    private function dealWithImages($carPageHtml){
        if(isset($carPageHtml->find("img[class=single-img-only]", 0)->src)){
            $this->imageSource = $carPageHtml->find("img[class=single-img-only]", 0)->src;
            $this->imageId = 'single';
            $this->downloadImage();
        }
        if(isset($carPageHtml->find("img[id=vs_photos_img_0]", 0)->src)){
            $this->imageSource = $carPageHtml->find("img[id=vs_photos_img_0]", 0)->src;
            $this->imageId = $carPageHtml->find("img[id=vs_photos_img_0]", 0)->id;
            $this->downloadImage();
        }
        if(isset($carPageHtml->find("img[id=vs_photos_img_1]", 0)->{'data-src'})){
            $this->imageSource = $carPageHtml->find("img[id=vs_photos_img_1]", 0)->{'data-src'};
            $this->imageId = $carPageHtml->find("img[id=vs_photos_img_1]", 0)->id;
            $this->downloadImage();
        }
        if(isset($carPageHtml->find("img[id=vs_photos_img_2]", 0)->{'data-src'})){
            $this->imageSource = $carPageHtml->find("img[id=vs_photos_img_2]", 0)->{'data-src'};
            $this->imageId = $carPageHtml->find("img[id=vs_photos_img_2]", 0)->id;
            $this->downloadImage();
        }
        if(isset($carPageHtml->find("img[id=vs_photos_img_3]", 0)->{'data-src'})){
            $this->imageSource = $carPageHtml->find("img[id=vs_photos_img_3]", 0)->{'data-src'};
            $this->imageId = $carPageHtml->find("img[id=vs_photos_img_3]", 0)->id;
            $this->downloadImage();
        }
    }
    private function downloadImage(){
        $data = CurlExecService::curlExec($this->imageSource);
        $path = "/storage/{$this->id}/{$this->imageId}.jpg";
        Storage::disk('public')->put($path, $data);
        $this->car->images()->create([
            'path' => $path
        ]);
    }
    private function createCarRecord(){
        if($car = Car::where('ad_id', $this->id)->first()){
        }else{
            $car = Car::create([
                'title' => $this->title,
                'description' => $this->description,
                'price' => $this->price,
                'mileage' => $this->mileage,
                'year' => $this->year,
                'phone' => $this->phone,
                'ad_id' => $this->id,
                'link_to_website' => $this->linkToCarPage
            ]);
        }
        return $car;
    }
}
