<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada data kategori
        $categories = Category::all();

        if ($categories->count() > 0) {
            Book::factory(10)->create([
                'category_id' => $categories->random()->id
            ]);
        }
    }
}
