<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Gallery>
 */
class GalleryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    
    public function definition(): array
    {

        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'title' => fake()->words(4, true),
            'description' => fake()->text($maxNbChars = 50)
        ];
    }
}
