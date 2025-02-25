<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Variant extends Model
{

    /** @use HasFactory<\Database\Factories\VariantFactory> */
    use HasFactory;

    protected $table = 'variants';

    protected $fillable = [
        'product_id',
        'color_id',
        'size_id',
        'price',
        'stock',
        'thumbnail'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function images() : HasMany
    {
        return $this->hasMany(Image::class);
    }

    public function orders() : BelongsToMany{
        return $this->belongsToMany(Order::class)->withPivot('quantity',"unit_price");
    }

    public function carts() : BelongsToMany{
        return $this->belongsToMany(Cart::class)->withPivot('quantity');
    }

    //Le prix est stocké en centimes, donc on le divise par 100 pour l'afficher, et on le multiplie par 100 pour le stocker.

    public function price() : Attribute{
        return new Attribute(
            function($value){
                return $value / 100;
            },
            function($value){
                return $value * 100;
            }
        );
    }
}
