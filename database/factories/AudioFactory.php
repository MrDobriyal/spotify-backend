<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Audio;
use Illuminate\Support\Str;
use App\Models\Artist;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\audio>
 */
class AudioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

     protected $model = Audio::class;

    public function definition(): array
    {
        $categories = [
            'hiphop', 'pop', 'rock', 'classical', 'jazz', 'electronic',
            'indian', 'metal', 'blues', 'reggae', 'folk', 'country', 'rnb', 'techno'
        ];
        $authorIds =Artist::all()->pluck('id')->toArray();
        return [
            'title' => $this->faker->sentence(1,true),
            'description' => $this->faker->sentence(3,true),
            'category' => $this->faker->randomElement($categories),
            'duration' => $this->faker->time('i:s'),
            'publish' => $this->faker->boolean(70), // 70% chance true
            'total_listen' => $this->faker->numberBetween(0, 100000),
            'publishedTime' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s'),
            'author_id' =>  $authorIds[array_rand($authorIds)],
        ];
    }

    // public function configure() //i dont use this as i have already created a command for this.
    // {
    //     return $this->afterCreating(function (Audio $audio) {
    //         // You can add random audio files here
    //         $audioFiles = [
    //             public_path('dummy/sample.mp3'),
    //             public_path('dummy/sample2.mp3')
    //         ];

    //         // Randomly assign an audio file to the created audio
    //         $randomAudioFile = $audioFiles[array_rand($audioFiles)];
            
    //         // Add media to the audio model
    //         $audio->addAudioMedia($randomAudioFile);
    //     });
    // }
}
