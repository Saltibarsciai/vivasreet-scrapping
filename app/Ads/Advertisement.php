<?php

namespace App\Services;

use App\Car;
use App\Image;
use Illuminate\Support\Facades\Storage;
use simple_html_dom\simple_html_dom_node;

class Advertisement extends ScrapingMethods
{
    /**@var string $imageSource*/
    private $imageSource;
    /**@var int $imageId*/
    private $imageId;
    /**@var Car $car*/
    private $car;

    /**
     * Advertisement constructor.
     * @param simple_html_dom_node $carHtml
     */
    public function __construct(simple_html_dom_node $carHtml)
    {
        parent::__construct($carHtml);
        $this->init();
    }
    private function init(): void
    {
        $this->createCarRecord();
    }

    private function createCarRecord(): void
    {
        //If record exists don't create
        if ($this->car = Car::where('ad_id', $this->advertId())->first()) {
            $this->car->update(['active' => true]);
            return;
        }
        //If not, then create
        $this->car = Car::create([
            'title' => $this->title(),
            'description' => $this->description(),
            'price' => $this->price(),
            'mileage' => $this->mileage(),
            'year' => $this->year(),
            'phone' => $this->phone(),
            'ad_id' => $this->advertId(),
            'link_to_website' => $this->link(),
            'active' => true,
        ]);
        $this->createImages();
    }

    private function createImages(): void
    {
        if ($this->hasProfile()) { // Profile photo has different html attribute
            $this->imageSource = $this->profile();
            $this->imageId = 'single_img_id';
            $this->downloadImage();
        } elseif ($this->hasPhoto(0, 'src')) { //photo with 0 index has different html attribute
            $this->imageSource = $this->getPhoto(0, 'src');
            $this->imageId = $this->getPhoto(0, 'id');
            $this->downloadImage();
        } else {
            $this->setPlaceholderImage();
        }

        for ($i = 1; $i <= 3; $i++) { //photos from second has similar html attributes
            if ($this->hasPhoto($i, 'data-src')) {
                $this->imageSource = $this->getPhoto($i, 'data-src');
                $this->imageId = $this->getPhoto($i, 'id');
                $this->downloadImage();
            } else {
                $this->setPlaceholderImage();
            }
        }
    }
    private function downloadImage()
    {
        $path = "{$this->advertId()}/{$this->imageId}.jpg";

        if (Image::where('path', '/storage/' . $path)->first()) {
            return true;
        }

        $data = app(CurlExecuteService::class)->curlExecute($this->imageSource);
        Storage::disk('public')->put($path, $data);
        $this->car->images()->create([
            'path' => '/storage/' . $path
        ]);
        return true;
    }
    private function setPlaceholderImage(): void
    {
        $this->car->images()->create([
            'path' => "https://colorlib.com/unite/wp-content/uploads/sites/7/2013/03/image-alignment-150x150.jpg"
        ]);
    }
}
