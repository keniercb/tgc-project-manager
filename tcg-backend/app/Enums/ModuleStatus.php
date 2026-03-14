<?php

namespace App\Enums;

enum ModuleStatus: string
{
    case  VALID = 'validated';
    case NEW = 'draft';
    case RFB = 'ready_for_build';

    public static function description(ModuleStatus $status): string
    {
        return match ($status) {
            self::VALID => 'Validated',
            self::NEW => 'Draft',
            self::RFB => 'Ready for build',
        };
    }

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, ModuleStatus::cases());
    }
}
