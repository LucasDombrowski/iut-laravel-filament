<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    /** @use HasFactory<\Database\Factories\DiscountFactory> */
    use HasFactory;

    protected $fillable = [
        'code',
        'start_at',
        'end_at',
        'min_price',
        'type',
        'value'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
