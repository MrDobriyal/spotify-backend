<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AudioController;
use App\Http\Controllers\Api\PlaylistController;
use App\Http\Controllers\Api\LikedSongController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ArtistController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// 🔐 Auth Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
  
    // 👤 User Info
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // 🎵 Audio Routes
    Route::get('/audios', [AudioController::class, 'index']);
    Route::post('/audios', [AudioController::class, 'store']);
    Route::get('/audios/{id}', [AudioController::class, 'show']);
    Route::put('/audios/{id}', [AudioController::class, 'update']);
    Route::delete('/audios/{id}', [AudioController::class, 'destroy']);
    Route::post('/audios/listen/{id}', [AudioController::class, 'countListen']);
    Route::get('/stream-audio/{id}', [AudioController::class, 'stream']);
    
    // 📀 Playlist Routes
    Route::get('/playlists', [PlaylistController::class, 'index']);
    Route::post('/playlists', [PlaylistController::class, 'store']);
    Route::get('/playlists/{id}', [PlaylistController::class, 'show']);
    Route::put('/playlists/{id}', [PlaylistController::class, 'update']);
    Route::delete('/playlists/{id}', [PlaylistController::class, 'destroy']);
    Route::post('/playlists/{id}/add-audio', [PlaylistController::class, 'addAudio']);
    Route::get('/playlists/{id}/audios', [PlaylistController::class, 'getAudios']);
    Route::post('/playlists/{id}/remove-audio', [PlaylistController::class, 'removeAudio']);

    // ❤️ Liked Songs
    Route::get('/liked-songs', [LikedSongController::class, 'index']);
    Route::post('/liked-songs/{audio_id}', [LikedSongController::class, 'like']);
    Route::delete('/liked-songs/{audio_id}', [LikedSongController::class, 'unlike']);
    Route::post('/liked-songs/{audio_id}/toggle', [LikedSongController::class, 'toggle']);

    Route::get('/artist/details/{id}',[ArtistController::class,'details']);
    Route::get('/artist',[ArtistController::class,'index']);
    Route::get('artist/{id}',[ArtistController::class,'show']);  //gives all song of that artist

    // 🔍 Search
    Route::get('/search', [AudioController::class, 'search']);

});
