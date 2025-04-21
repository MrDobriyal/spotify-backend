<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LikedSongController extends Controller
{
    public function index()
    {
        $likedSongs = LikedSong::with('audio.media')->where('user_id', Auth::id())->get();
        return response()->json($likedSongs);
    }

    public function like($audio_id)
    {
        $exists = LikedSong::where('user_id', Auth::id())
                    ->where('audio_id', $audio_id)
                    ->exists();

        if ($exists) {
            return response()->json(['message' => 'Already liked'], 409);
        }

        LikedSong::create([
            'user_id' => Auth::id(),
            'audio_id' => $audio_id
        ]);

        return response()->json(['message' => 'Song liked']);
    }

    public function unlike($audio_id)
    {
        $deleted = LikedSong::where('user_id', Auth::id())
                    ->where('audio_id', $audio_id)
                    ->delete();

        return response()->json(['message' => $deleted ? 'Song unliked' : 'Not found'], $deleted ? 200 : 404);
    }

    public function toggle($audio_id)
{
    $userId = auth()->id();

    $likedSong = LikedSong::where('user_id', $userId)
                          ->where('audio_id', $audio_id)
                          ->first();

    if ($likedSong) {
        $likedSong->delete();
        return response()->json(['message' => 'Song unliked']);
    } else {
        LikedSong::create([
            'user_id' => $userId,
            'audio_id' => $audio_id
        ]);
        return response()->json(['message' => 'Song liked']);
    }
}
}
