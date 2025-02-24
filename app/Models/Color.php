<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    /** @use HasFactory<\Database\Factories\ColorFactory> */
    use HasFactory;

    protected $fillable = [
        'label',
        'hex'
    ];

    public function variants()
    {
        return $this->hasMany(Variant::class);
    }
}
