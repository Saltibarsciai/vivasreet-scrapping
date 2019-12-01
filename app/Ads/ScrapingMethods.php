<?php

namespace App\Services;

use KubAT\PhpSimple\HtmlDomParser;

class ScrapingMethods
{
    private $carPageHtml;
    private $linkToCarPage;

    public function __construct($carHtml)
    {
        $this->linkToCarPage = $carHtml->children(0)->href;
        $carPageResponse = CurlExecService::curlExec($this->linkToCarPage);
        $this->carPageHtml = HtmlDomParser::str_get_html($carPageResponse);
    }

    public function title()
    {
        $selector = "h1[class=kiwii-font-xlarge kiwii-margin-none kiwii-font-weight-semibold]";
        return $this->carPageHtml->find($selector, 0)->plaintext;
    }

    public function description()
    {
        $selector = "div[class=shortdescription font-panel-content]";
        return $this->carPageHtml->find($selector, 0)->plaintext;
    }

    public function advertId()
    {
        //Id does not have it's own container, needs filtering
        $selector = "ul[class=kiwii-description-footer]";
        $fieldWithId = $this->carPageHtml->find($selector, 0)->children(0)->plaintext;
        $modifiedFieldWithId = str_replace("Ad ID", "", $fieldWithId);
        return str_replace(" ", "", $modifiedFieldWithId);
    }
    public function price()
    {
        $selector = "td[class=kiwii-font-weight-semibold kiwii-font-primary kiwii-font-default kiwii-padding-top-xxxsmall]";
        return $this->carPageHtml->find($selector, 0)->plaintext;
    }
    public function year()
    {
        $selector = "table[id=details-tbl-specs]";
        return $this->carPageHtml->find($selector, 0)->children(4)->children(1)->plaintext;
    }
    public function mileage()
    {
        $selector = "table[id=details-tbl-specs]";
        return $this->carPageHtml->find($selector, 0)->children(6)->children(1)->plaintext;
    }
    public function phone()
    {
        $selector = "span[class=phone_link]";
        //vendor can choose to provide email instead of phone
        if(isset($this->carPageHtml->find($selector, 0)->{'data-phone-number'})){
            return $this->carPageHtml->find($selector, 0)->{'data-phone-number'};
        }
        return false;

    }
    public function link()
    {
        return $this->linkToCarPage;
    }
    public function profile()
    {
        $selector = "img[class=single-img-only]";
        if(isset($this->carPageHtml->find($selector, 0)->src)){
            return $this->carPageHtml->find($selector, 0)->src;
        }
        return false;
    }
    public function get($index, $tag)
    {
        $selector = "img[id=vs_photos_img_$index]";
        if(isset($this->carPageHtml->find($selector, 0)->{$tag})){
            return $this->carPageHtml->find($selector, 0)->{$tag};
        }
        return false;
    }

}
