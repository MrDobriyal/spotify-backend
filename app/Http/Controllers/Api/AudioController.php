<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Audio;

class AudioController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'duration' => 'string',
            'author_id' => 'integer',
            'audio' => 'required|mimes:mp3,wav,ogg|max:20480',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        $audio = Audio::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'category' => $validated['category'],
            'duration' => $validated['duration'],
            'publish' => false,
            'total_listen' => 0,
            'publishedTime' => null,
            'author_id' => $validated['author_id'],
        ]);

        // Add files to media library
        if ($request->hasFile('audio')) {
            $audio->addMediaFromRequest('audio')->toMediaCollection('audio_files');
        }

        if ($request->hasFile('image')) {
            $audio->addMediaFromRequest('image')->toMediaCollection('cover_images');
        }

        return response()->json($audio->load('media'), 201);
    }

    public function update(Request $request, $id)
{
    $audio = Audio::findOrFail($id);

    $validated = $request->validate([
        'title' => 'sometimes|required|string|max:255',
        'description' => 'nullable|string',
        'category' => 'sometimes|required|string|in:hiphop,pop,rock,classical,jazz,electronic,indian,metal,blues,reggae,folk,country,rnb,techno',
        'duration' => 'sometimes|string',
        'author_id' => 'sometimes|integer',
        'publish' => 'sometimes|boolean',
        'publishedTime' => 'nullable|string',
        'audio' => 'nullable|mimes:mp3,wav,ogg|max:20480',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
    ]);

    $audio->update($validated);

    // Replace existing audio file if a new one is uploaded
    if ($request->hasFile('audio')) {
        $audio->clearMediaCollection('audio_files');
        $audio->addMediaFromRequest('audio')->toMediaCollection('audio_files');
    }

    // Replace existing image file if a new one is uploaded
    if ($request->hasFile('image')) {
        $audio->clearMediaCollection('cover_images');
        $audio->addMediaFromRequest('image')->toMediaCollection('cover_images');
    }

    return response()->json($audio->load('media'), 200);
}

    public function show($id)
    {
        $audio = Audio::with('media')->find($id);
        if (!$audio) return response()->json(['message' => 'Audio not found'], 404);
        $data= [
            'id' => $audio->id,
            'title' => $audio->title,
            'description' => $audio->description,
            'category' => $audio->category,
            'duration' => $audio->duration,
            'author_id' => $audio->author_id,
            'publish' => $audio->publish,
            'publishedTime' => $audio->publishedTime,
            // 'audio_url' => url("api/stream-audio/{$audio->id}"),
            'audio_url' => $audio->getFirstMediaUrl('audio_files'),
            'image_url' => $audio->getFirstMediaUrl('cover_images'),
            'created_at' => $audio->created_at,
            'updated_at' => $audio->updated_at,
        ];

        return response()->json($data, 200);
    }

    public function index()
    {   //Spatie Media Library automatically provides a media relationship on any model that uses the InteractsWithMedia trait (which you're using in your Audio model).
        $audios = Audio::with('media')->where('publish',1)->get()->map(function ($audio) {
            return [
                'id' => $audio->id,
                'title' => $audio->title,
                'description' => $audio->description,
                'category' => $audio->category,
                'duration' => $audio->duration,
                'author_id' => $audio->author_id,
                'total_listen'=>$audio->total_listen,
                'publish' => $audio->publish,
                'publishedTime' => $audio->publishedTime,
                // 'audio_url' => url("api/stream-audio/{$audio->id}"),
                'audio_url' => $audio->getFirstMediaUrl('audio_files'),
                'image_url' => $audio->getFirstMediaUrl('cover_images'),
                'created_at' => $audio->created_at,
                'updated_at' => $audio->updated_at,
            ];
        });
    
        return response()->json($audios);
    }

    public function destroy($id)
{
    $audio = Audio::findOrFail($id);

    // Delete associated media files
    $audio->clearMediaCollection('audio_files');
    $audio->clearMediaCollection('cover_images');

    // Delete audio record
    $audio->delete();

    return response()->json(['message' => 'Audio deleted successfully.']);
}

public function countListen($id)
{
    $audio = Audio::findOrFail($id);
    $audio->increment('total_listen');

    return response()->json(['message' => 'Listen counted!']);
}


public function stream($id)
{
    $audio = Audio::findOrFail($id);
    $media = $audio->getFirstMedia('audio_files');
    
    if (!$media) {
        abort(404, 'Audio not found.');
    }

    $path = $media->getPath();
    $size = filesize($path);
    $mime = $media->mime_type ?? 'audio/mpeg';
    dd($mime);
    $headers = [
        'Content-Type' => $mime,
        'Accept-Ranges' => 'bytes',
        'Content-Length' => $size,
        'Cache-Control' => 'public, must-revalidate, max-age=0',
        'Pragma' => 'public',
        'Accept-Ranges' => 'bytes',
        'Access-Control-Allow-Origin' => '*', 
        'Access-Control-Allow-Methods' => 'GET, OPTIONS',
        'Access-Control-Allow-Headers' => 'Origin, Content-Type, Accept',
        'Expires' => 0,
    ];
    return response()->file($path, $headers);
}

}
