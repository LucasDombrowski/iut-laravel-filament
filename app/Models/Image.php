<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    /** @use HasFactory<\Database\Factories\ImageFactory> */
    use HasFactory;

    protected $fillable = [
        'variant_id',
        'url',
        'alt'
    ];

    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }
}
