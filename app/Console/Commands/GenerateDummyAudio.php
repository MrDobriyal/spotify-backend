<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Audio;
use Illuminate\Support\Facades\Storage;

class GenerateDummyAudio extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:dummy-audio';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate dummy audio records with attached media files';

    /**
     * Execute the console command.
     */

    public function handle()
    {

        $images = [
            public_path('dummy/dummy.jpeg'),
            public_path('dummy/dummy1.png'),
            public_path('dummy/dummy2.png'),
            public_path('dummy/dummy3.png'),
        ];

        // List of possible audio file paths
        $audios = [
            public_path('dummy/dummy.mp3'),
            public_path('dummy/dummy1.mp3'),
            public_path('dummy/dummy2.mp3'),
            public_path('dummy/dummy3.mp3'),
        ];

       
    // Fetch existing Audio records (you can specify a limit if needed)
        $audioRecords = Audio::all();

        // Ensure we have some records to attach media
        if ($audioRecords->isEmpty()) {
            $this->info('No existing audio records found!');
            return;
        }

        $this->info('Attaching dummy media to existing audio records...');

        // Loop through each record and add media
        $audioRecords->each(function ($audio) use ($images ,$audios) {
            // Add image to the media collection
            $randomImage = $images[array_rand($images)];
            $randomAudio = $audios[array_rand($audios)];
            $audio->addMedia($randomImage)  // Replace with your image path
                  ->preservingOriginal()
                  ->toMediaCollection('cover_images');

            // Add audio file to the media collection
            $audio->addMedia($randomAudio)  // Replace with your audio file path
                  ->preservingOriginal()
                  ->toMediaCollection('audio_files');
        });

        $this->info('Media attached to existing audio records successfully!');
    }
    
}
