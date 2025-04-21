<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Artist;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Artist>
 */
class ArtistFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'=>$this->faker->name(),
            'bio'=>$this->faker->sentences(3,true),
        ];
    }

    public function configure(){
        $images = [
            public_path('dummy/dummy.jpeg'),
            public_path('dummy/dummy1.png'),
            public_path('dummy/dummy2.png'),
            public_path('dummy/dummy3.png'),
        ];

        $randomImage = $images[array_rand($images)];

        return $this->afterCreating(function (Artist $artist) use($images) {
            $randomImage = $images[array_rand($images)];
            $artist->addImage($randomImage);
        });
    }
}
