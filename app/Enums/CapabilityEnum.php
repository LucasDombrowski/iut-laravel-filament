<?php

namespace App\Enums;

enum CapabilityEnum: string
{
    case UseBackoffice = 'use_backoffice';

    public function label(): string
    {
        return match ($this) {
            self::UseBackoffice => 'Use Backoffice',
        };
    }
}