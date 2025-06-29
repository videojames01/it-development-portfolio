<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'section' => fake()->randomElement(['Dev Ops', 'Usability', 'Innovation']),
            'name' => fake()->sentence(3),
            'complete' => fake()->boolean,
            'user_id' => User::factory()
        ];
    }
}
