<?php

namespace App\Models;

use App\Enums\DiscountTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'discount_id',
        'status',
        'address_number',
        'address_street',
        'address_complement',
        'address_city',
        'address_zip_code',
        'address_country_code',
    ];

    protected $appends = [
        'total_price'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function variants()
    {
        return $this->belongsToMany(Variant::class)->withPivot('quantity',"unit_price")->using(OrderVariant::class);
    }

    public function getTotalPriceAttribute()
    {
        $total = 0;
        foreach ($this->variants as $variant) {
            $total += $variant->pivot->quantity * $variant->pivot->unit_price;
        }
        if ($this->discount) {
            if($total >= $this->discount->min_price){
                if($this->discount->type == DiscountTypeEnum::Percentage){
                    $total = $total - ($total * $this->discount->value / 100);
                }else if($this->discount->type == DiscountTypeEnum::Fixed){
                    $total = $total - $this->discount->value;
                }
            }
        }
        return $total;
    }
}
