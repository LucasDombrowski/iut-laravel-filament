<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    /** @use HasFactory<\Database\Factories\RoleFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'slug'
    ];

    public function capabilities() : BelongsToMany{
        return $this->belongsToMany(Capability::class);
    }

    public function users() : HasMany{
        return $this->hasMany(User::class);
    }
}
