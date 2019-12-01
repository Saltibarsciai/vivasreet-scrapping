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
        $this->createImages();
    }

    private function createCarRecord(): void
    {
        //If record exists
        if ($record = Car::where('ad_id', $this->advertId())->first()) {
            $this->car = $record;
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
            'link_to_website' => $this->link()
        ]);
    }
    private function createImages(): void
    {
        //specific tag if only profile photo provided
        if ($this->imageSource = $this->profile()) {
            $this->imageId = 'single';
            $this->downloadImage();
        }
        //specific tag if profile photo provided + 1
        if ($this->imageSource = $this->getPhoto(0, 'src')) {
            $this->imageId = $this->getPhoto(0, 'id');
            $this->downloadImage();
        }
        for ($i = 1; $i <= 3; $i++) {
            if ($this->imageSource = $this->getPhoto($i, 'data-src')) {
                $this->imageId = $this->getPhoto($i, 'id');
                $this->downloadImage();
            } else {
                $this->setPlaceholderImage();
            }
        }
    }
    private function downloadImage(): void
    {
        $path = "{$this->advertId()}/{$this->imageId}.jpg";
        //Return if already downloaded
        if (Image::where('path', '/storage/' . $path)->first()) {
            return;
        }
        $data = CurlExecService::curlExec($this->imageSource);
        Storage::disk('public')->put($path, $data);
        $this->car->images()->create([
            'path' => '/storage/' . $path
        ]);
    }
    private function setPlaceholderImage(): void
    {
        $this->car->images()->create([
            'path' => "https://colorlib.com/unite/wp-content/uploads/sites/7/2013/03/image-alignment-150x150.jpg"
        ]);
    }
}
