<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Database\Factories\AudioFactory; 

class Audio extends Model implements HasMedia
{   
    use InteractsWithMedia ,HasFactory;
    protected $table='audio';
    protected $fillable=['title','description',
'path','duration','publish','total_listen','publishedTime'];

public function playlists()
{
    return $this->belongsToMany(Playlist::class, 'audio_playlist');
}

public function likedByUsers()
{
    return $this->hasMany(LikedSong::class);
}

protected static function newFactory(){
    return new AudioFactory();
}

public function addAudioMedia($audioFile)
{
    $this->addMedia($audioFile)
         ->preservingOriginal()
         ->toMediaCollection('audio_files');
}

}
