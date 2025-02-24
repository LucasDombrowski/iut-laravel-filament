<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'slug'
    ];

    public function variants()
    {
        return $this->hasMany(Variant::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
