<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LikedSong extends Model
{
    protected $fillable = ['user_id', 'audio_id'];

    public function audio()
    {
        return $this->belongsTo(Audio::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
