<?php

namespace App\Enums;

enum ArtifactType: string
{
    case SA = 'strategic_alignment';
    case BP = 'big_picture';
    case DB = 'domain_breakdown';
    case MM = 'module_matrix';
    case ME = 'module_engineering';
    case SAR = 'system_architecture';
    case PS = 'phase_scope';

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, ArtifactType::cases());
    }

    public static function description(ArtifactType $type): string
    {
        return match ($type) {
            self::SA => 'Strategic Alignment',
            self::BP => 'Big Picture',
            self::DB => 'Domain Breakdown',
            self::MM => 'Module Matrix',
            self::ME => 'Module Engineering',
            self::SAR => 'System Architecture',
            self::PS => 'Phase Scope',
        };
    }
}
