<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Campaign>
 */
class CampaignFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->words(3, true);

        return [
            'name' => $name,
            'slug' => str($name)->slug(),
            'quota_count' => $this->faker->numberBetween(10, 1000),
            'quota_value' => $this->faker->numberBetween(500, 10000),
            'per_user_limit' => $this->faker->numberBetween(1, 5),
        ];
    }
}
