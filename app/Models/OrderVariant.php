<?php

namespace App\Models;

use Dom\Attr;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderVariant extends Pivot
{
    public function unitPrice() : Attribute
    {
        return new Attribute(
            fn($value) => $value/100,
            fn($value) => $value*100
        );
    }
}
