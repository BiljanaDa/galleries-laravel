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
        $user = User::inRandomOrder()->first();

        // Check if a user is found
        $userId = $user ? $user->id : User::factory()->create()->id;
    
        return [
            'user_id' => $userId,
            'title' => fake()->words(4, true),
            'description' => fake()->text($maxNbChars = 50)
        ];
    }
}
