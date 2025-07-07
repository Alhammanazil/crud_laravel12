<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'author' => fake()->name(),
            'publisher' => fake()->company(),
            'publication_year' => fake()->numberBetween(1990, 2024),
            'category_id' => Category::factory(),
            'stock' => fake()->numberBetween(0, 100),
            'price' => fake()->randomFloat(2, 10000, 500000), // Harga 10rb - 500rb
            'isbn' => fake()->unique()->isbn13(),
            'description' => fake()->paragraph(3),
        ];
    }
}
