<?php

namespace App\Enums;

enum DiscountTypeEnum : string{
    case Percentage = 'percentage';
    case Fixed = 'fixed';

    public function label(): string
    {
        return match ($this) {
            self::Percentage => 'Percentage',
            self::Fixed => 'Fixed',
        };
    }
}