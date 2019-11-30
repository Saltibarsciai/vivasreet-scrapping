<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
      'path',
      'car_id'
    ];
    public function car(){
        return $this->hasMany(Car::class);
    }
}
