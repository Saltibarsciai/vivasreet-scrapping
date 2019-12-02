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

    private const PROFILE_SELECTOR = "img[class=single-img-only]";

    /**
     * ScrapingMethods constructor.
     * @param simple_html_dom_node $carHtml
     */
    public function __construct(simple_html_dom_node $carHtml)
    {
        $this->linkToCarPage = $carHtml->children(0)->href;
        $carPageResponse = app(CurlExecuteService::class)->curlExecute($this->linkToCarPage);
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
     * @param string $selector
     * @return bool
     */
    public function hasPhone(string $selector): bool
    {
        return isset($this->carPageHtml->find($selector, 0)->{'data-phone-number'});
    }

    /**
     * @return integer
     */
    public function phone()
    {
        $selector = "span[class=phone_link]";
        if ($this->hasPhone($selector)) {
            return $this->carPageHtml->find($selector, 0)->{'data-phone-number'};
        }
        return 0;
    }

    /**
     * @return string
     */
    public function link(): string
    {
        return $this->linkToCarPage;
    }

    /**
     * @return bool
     */
    public function hasProfile(): bool
    {
        return isset($this->carPageHtml->find("img[class=single-img-only]", 0)->src);
    }
    /**
     * @return string
     */
    public function profile()
    {
        if ($this->hasProfile()) {
            return $this->carPageHtml->find(self::PROFILE_SELECTOR, 0)->src;
        }
        return "Not provided";
    }

    /**
     * @param int $index
     * @param string $tag
     * @return bool
     */
    public function hasPhoto(int $index, string $tag)
    {
        $selector = "img[id=vs_photos_img_$index]";
        return isset($this->carPageHtml->find($selector, 0)->{$tag});
    }

    /**
     * @param int $index
     * @param string $tag
     * @return string
     */
    public function getPhoto(int $index, string $tag)
    {
        $selector = "img[id=vs_photos_img_$index]";
        if ($this->hasPhoto($index, $tag)) {
            return $this->carPageHtml->find($selector, 0)->{$tag};
        }
        return "Not provided";
    }

}
