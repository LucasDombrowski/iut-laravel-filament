<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Capability extends Model
{
    /** @use HasFactory<\Database\Factories\CapabilityFactory> */
    use HasFactory;

    protected $fillable = [
        'label',
        'slug'
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
