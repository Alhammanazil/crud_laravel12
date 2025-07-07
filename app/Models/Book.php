<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    /** @use HasFactory<\Database\Factories\BookFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'publisher',
        'publication_year',
        'category_id',
        'stock',
        'price',
        'isbn',
        'description'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'publication_year' => 'integer',
        'stock' => 'integer',
    ];

    // Relasi Book belongsTo Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
