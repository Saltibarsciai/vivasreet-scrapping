<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        'title',
        'description',
        'price',
        'mileage',
        'year',
        'phone',
        'ad_id',
        'link_to_website'
    ];
    protected $with = ['images'];
    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
