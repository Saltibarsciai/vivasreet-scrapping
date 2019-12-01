<?php

namespace App\Services;

use KubAT\PhpSimple\HtmlDomParser;
use simple_html_dom\simple_html_dom_node;

class ScrapingMethods
{

    /**@var simple_html_dom_node $carPageHtml*/
    private $carPageHtml;
    /**@var string $linkToCarPage*/
    private $linkToCarPage;

    /**
     * ScrapingMethods constructor.
     * @param simple_html_dom_node $carHtml
     */
    public function __construct(simple_html_dom_node $carHtml)
    {
        $this->linkToCarPage = $carHtml->children(0)->href;
        $carPageResponse = CurlExecService::curlExec($this->linkToCarPage);
        $this->carPageHtml = HtmlDomParser::str_get_html($carPageResponse);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        $selector = "h1[class=kiwii-font-xlarge kiwii-margin-none kiwii-font-weight-semibold]";
        return $this->carPageHtml->find($selector, 0)->plaintext;
    }

    /**
     * @return string
     */
    public function description(): string
    {
        $selector = "div[class=shortdescription font-panel-content]";
        return $this->carPageHtml->find($selector, 0)->plaintext;
    }

    /**
     * @return int
     */
    public function advertId(): int
    {
        //Id does not have it's own container, needs filtering
        $selector = "ul[class=kiwii-description-footer]";
        $fieldWithId = $this->carPageHtml->find($selector, 0)->children(0)->plaintext;
        $modifiedFieldWithId = str_replace("Ad ID", "", $fieldWithId);
        return str_replace(" ", "", $modifiedFieldWithId);
    }

    /**
     * @return string
     */
    public function price(): string
    {
        $selector =
            "td[class=kiwii-font-weight-semibold kiwii-font-primary kiwii-font-default kiwii-padding-top-xxxsmall]";
        return $this->carPageHtml->find($selector, 0)->plaintext;
    }

    /**
     * @return string
     */
    public function year(): string
    {
        $selector = "table[id=details-tbl-specs]";
        return $this->carPageHtml->find($selector, 0)->children(4)->children(1)->plaintext;
    }

    /**
     * @return string
     */
    public function mileage(): string
    {
        $selector = "table[id=details-tbl-specs]";
        return $this->carPageHtml->find($selector, 0)->children(6)->children(1)->plaintext;
    }

    /**
     * @return bool
     */
    public function phone(): bool
    {
        $selector = "span[class=phone_link]";
        //vendor can choose to provide email instead of phone
        if (isset($this->carPageHtml->find($selector, 0)->{'data-phone-number'})) {
            return $this->carPageHtml->find($selector, 0)->{'data-phone-number'};
        }
        return false;
    }

    /**
     * @return string
     */
    public function link(): string
    {
        return $this->linkToCarPage;
    }

    /**
     * @return bool|string
     */
    public function profile()
    {
        $selector = "img[class=single-img-only]";
        if (isset($this->carPageHtml->find($selector, 0)->src)) {
            return $this->carPageHtml->find($selector, 0)->src;
        }
        return false;
    }

    /**
     * @param $index
     * @param $tag
     * @return bool|string
     */
    public function getPhoto($index, $tag)
    {
        $selector = "img[id=vs_photos_img_$index]";
        if (isset($this->carPageHtml->find($selector, 0)->{$tag})) {
            return $this->carPageHtml->find($selector, 0)->{$tag};
        }
        return false;
    }

}
