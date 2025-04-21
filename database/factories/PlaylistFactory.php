<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Playlist;
use Illuminate\Support\Str;
use Database\Factories\PlaylistFactory;
use App\Models\Audio;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PlaylistFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model =Playlist::class;

    public function definition(): array
    {
        return [
                'title' =>$this->faker->name(),
                'description' =>$this->faker->sentences(3,true),
                'type'=>$this->faker->randomElement([
                'hiphop',
                'pop',
                'rock',
                'classical',
                'jazz',
                'electronic',
                'indian',
                'metal',
                'blues',
                'reggae',
                'folk',
                'country',
                'rnb',
                'techno'
           ])
        ];
    }

    public function configure()
    {
        // afterCreating(): runs after the model is created and saved to the database.
        // afterMaking(): runs after the model is made (in memory), but before it's saved.
        return $this->afterCreating(function (Playlist $playlist) {
            // Fetch all audio IDs
            $audioIds = Audio::all()->pluck('id')->toArray();

            // Ensure at least 10 random audio tracks are attached
            $randomAudioIds = array_rand(array_flip($audioIds), 10);

            // Attach random audio tracks to this playlist
            $playlist->audios()->attach($randomAudioIds);
        });
    }
}
