<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Playlist;

class PlaylistController extends Controller
{

    public function index(Request $request){
        $playlist = Playlist::all()->map(function($playlist){
            return [
        "id"=> $playlist->id,
        "title"=> $playlist->title,
        "description"=> $playlist->description,
        "image"=> $playlist->getFirstMediaUrl('playlist_image'),
        ];
    });
        return response()->json($playlist);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'nullable|string|in:hiphop,pop,rock,classical,jazz,electronic,indian,metal,blues,reggae,folk,country,rnb,techno',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $playlist = Playlist::create($validated);

        if ($request->hasFile('image')) {
            $playlist->addMediaFromRequest('image')->toMediaCollection('playlist_image');
        }

        return response()->json($playlist, 201);
    }

    // GET /playlists/{id}
    public function show($id)
    {
        $playlist = Playlist::with('media')->findOrFail($id);
        return response()->json($playlist);
    }

    // PUT /playlists/{id}
    public function update(Request $request, $id)
    {
        $playlist = Playlist::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'sometimes|string|in:hiphop,pop,rock,classical,jazz,electronic,indian,metal,blues,reggae,folk,country,rnb,techno',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $playlist->update($validated);

        if ($request->hasFile('image')) {
            $playlist->clearMediaCollection('playlist_image');
            $playlist->addMediaFromRequest('image')->toMediaCollection('playlist_image');
        }

        return response()->json($playlist);
    }

    // DELETE /playlists/{id}
    public function destroy($id)
    {
        $playlist = Playlist::findOrFail($id);
        $playlist->delete();
        return response()->json(['message' => 'Playlist deleted successfully.']);
    }


    public function addAudio(Request $request, $id)
{
    $request->validate([
        'audio_id' => 'required|exists:audio,id',
    ]);

    $playlist = Playlist::findOrFail($id);
    if ($playlist->audios()->where('audio_id', $request->audio_id)->exists()) {
        return response()->json(['message' => 'Audio is already in the playlist.'], 409); // 409 Conflict
    }
    $playlist->audios()->attach($request->audio_id);

    return response()->json(['message' => 'Audio added to playlist.']);
}


public function getAudios($id)
{
    $playlist = Playlist::with(['audios.media'])->findOrFail($id);

    // Get only published audios
    $publishedAudios = $playlist->audios->where('publish', true)->map(function ($audio) {
        return [
            'id' => $audio->id,
            'title' => $audio->title,
            'description' => $audio->description,
            'category' => $audio->category,
            'duration' => $audio->duration,
            'total_listen' => $audio->total_listen,
            'publishedTime' => $audio->publishedTime,
            'author_id' => $audio->author_id,
            'created_at' => $audio->created_at,
            'updated_at' => $audio->updated_at,
            // 'audio_url' => url("api/stream-audio/{$audio->id}"),
            'audio_url' => $audio->getFirstMediaUrl('audio_files'),
            'image_url' => $audio->getFirstMediaUrl('cover_images'),
        ];
    });

    $data = [
        'playlist_id' => $playlist->id,
        'title' => $playlist->title,
        'description' => $playlist->description,
        'type' => $playlist->type,
        'audios' => $publishedAudios,
    ];

    return response()->json($data);
}


public function removeAudio(Request $request, $id)
{
    $request->validate([
        'audio_id' => 'required|exists:audio,id',
    ]);

    $playlist = Playlist::findOrFail($id);
    $playlist->audios()->detach($request->audio_id);

    return response()->json(['message' => 'Audio removed from playlist.']);
}
}
