<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Artist;
use App\Models\Audio;

class ArtistController extends Controller
{
    public function index(){
        $audios = Artist::with('media')->get()->map(function ($artist) {
            return [
                'id' => $artist->id,
                'name'=>$artist->name,
                'bio'=>$artist->bio,
                'image' => $artist->getFirstMediaUrl('artist_image'),
                'created_at' => $artist->created_at,
                'updated_at' => $artist->updated_at,
            ];
        });
        return response()->json($audios);
    }


    public function show(Request $request,$id){
        $audios = Audio::with('media')->where('author_id',$id)->get()->map(function ($audio) {
            return [
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
        });
    
        return response()->json($audios);
        
    }




    public function addImage(Request $request, $id)
    {
        $request->validate([
            'image' => 'required',
        ]);
    
        $artist = Artist::findOrFail($id);
        if ($request->hasFile('image')) {
            $artist->addMediaFromRequest('image')->toMediaCollection('artist_image');
        }
        return response()->json($artist);
    }

    public function details($id){
        $artist = Artist::findOrFail($id);
        return response()->json([
            'id' => $artist->id,
        'name' => $artist->name,
        'bio' => $artist->bio,
        'image_url' => $artist->getFirstMediaUrl('artist_image'),
        'created_at' => $artist->created_at,
        'updated_at' => $artist->updated_at,
        'albums' => $artist->albums,
        'audios' => $artist->audios,
        ]);
    }
}
