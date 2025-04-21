<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Database\Factories\ArtistFactory;

class Artist extends Model implements HasMedia
{
    use InteractsWithMedia ,HasFactory;
    protected $table='author';

    protected $fillable=['bio','name'];

    protected static function newFactory(){
        // return new ArtistFactory();   //configure and other method wont work with this
        return ArtistFactory::new();
    }

    public function addImage($image)
{
    $this->addMedia($image)
         ->preservingOriginal()
         ->toMediaCollection('artist_image');
}
}
