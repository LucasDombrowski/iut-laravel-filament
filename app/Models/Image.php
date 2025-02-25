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
        'alt',
        "order"
    ];

    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }

    //Lors de la création, on modifie la colonne order pour qu'elle s'adapte au nombre d'images associées au variant.
    //Ceci ne s'applique que si order vaut null.
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($image) {
            if ($image->order === null) {
                $image->order = $image->variant->images()->count();
            }
        });
    }

}
