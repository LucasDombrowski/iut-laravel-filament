<?php
namespace App\Enums;

enum OrderStatusEnum: string {
    case Pending = 'pending';
    case Processing = 'processing';
    case Ccompleted = 'completed';
    case Cancelled = 'cancelled';

    public function label(): string {
        return match ($this) {
            self::Pending => 'Pending',
            self::Processing => 'Processing',
            self::Ccompleted => 'Completed',
            self::Cancelled => 'Cancelled',
        };
    }
}