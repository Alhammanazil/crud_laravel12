<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement([
                'Fiction',
                'Non-Fiction',
                'Science',
                'Technology',
                'History',
                'Biography',
                'Romance',
                'Mystery',
                'Fantasy',
                'Self-Help'
            ]),
            'description' => fake()->sentence(10),
            'is_active' => fake()->boolean(90),
        ];
    }
}
