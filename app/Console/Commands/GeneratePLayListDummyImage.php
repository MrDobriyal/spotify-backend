<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Playlist;
class GeneratePLayListDummyImage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate-playlistdummyimage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
       
    // Fetch existing Audio records (you can specify a limit if needed)
        $playlistRecords = Playlist::all();

        // Ensure we have some records to attach media
        if ($playlistRecords->isEmpty()) {
            $this->info('No existing Playlist records found!');
            return;
        }

        $this->info('Attaching dummy media to existing Playlist records...');

        // Loop through each record and add media
        $playlistRecords->each(function ($playlist) use ($images) {
            // Add image to the media collection
            $randomImage = $images[array_rand($images)];
            $playlist->addMedia($randomImage)  // Replace with your image path
                  ->preservingOriginal()
                  ->toMediaCollection('playlist_image');
        });

        $this->info('Media attached to existing Playlist records successfully!');
    }
}
