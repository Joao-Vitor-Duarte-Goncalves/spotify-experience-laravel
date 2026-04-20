<?php

namespace Database\Factories;

use App\Models\RecentTrack;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RecentTrack>
 */
class RecentTrackFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'spotify_id' => 'spec-id-' . $this->faker->unique()->uuid(),
            'name' => $this->faker->sentence(3),
            'artist' => $this->faker->name,
            'played_at' => now(),
        ];
    }
}
