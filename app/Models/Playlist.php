<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Database\Factories\PlaylistFactory;

class Playlist extends Model implements HasMedia
{
    use InteractsWithMedia ,HasFactory;
    protected $table="playlist";

    protected $fillable = [
        'title', 'description','type'
    ];

    public function audios()
{
    return $this->belongsToMany(Audio::class, 'audio_playlist');
}

    protected static function newFactory(){
        $images = [
            public_path('dummy/dummy.jpeg'),
            public_path('dummy/dummy1.png'),
            public_path('dummy/dummy2.png'),
            public_path('dummy/dummy3.png'),
        ];

        $randomImage = $images[array_rand($images)];

        return PlaylistFactory::new()->afterCreating(function ($playlist) use ($randomImage) {
            // Add an image to the media collection
            $playlist->addMedia($randomImage)  
                  ->preservingOriginal()
                  ->toMediaCollection('thumbnail');
        });
    }
}
