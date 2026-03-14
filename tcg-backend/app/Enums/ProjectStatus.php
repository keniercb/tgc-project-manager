<?php

namespace App\Enums;

enum ProjectStatus: string
{
    case DRAFT = "draft";
    case DISCOVERY = "discovery";
    case  EXECUTION = "execution";
    case DELIVERED = "delivered";

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, ProjectStatus::cases());
    }

    public static function status(ProjectStatus $type): string
    {
        return match ($type) {
            self::DRAFT => 'Draft',
            self::DISCOVERY => 'Discovery',
            self::EXECUTION => 'In Execution',
            self::DELIVERED => 'Delivered',
        };
    }

}
