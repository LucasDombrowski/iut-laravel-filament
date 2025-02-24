<?php

namespace App\Enums;

enum RoleEnum: string
{
    case Admin = 'admin';
    case User = 'user';
    case Vip = 'vip';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrator',
            self::User => 'User',
            self::Vip => 'VIP',
        };
    }

    public function capabilities(): array
    {
        return match ($this) {
            self::Admin => [
                CapabilityEnum::UseBackoffice,
            ],
            self::User => [

            ],
            self::Vip => [
                
            ],
        };
    }
}