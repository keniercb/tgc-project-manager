<?php

namespace App\Enums;

enum ArtifactState: string
{
    case  IN_PROGRESS = "in progress";
    case  BLOCKED = "blocked";
    case  COMPLETED = "done";
    case NOT_STARTED = "not started";

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, ArtifactType::cases());
    }

    public static function description(ArtifactState $state): string
    {
        return match ($state) {
            self::IN_PROGRESS => 'In progress',
            self::BLOCKED => 'Blocked',
            self::COMPLETED => 'Done',
            self::NOT_STARTED => 'Not started',
        };

    }
}
