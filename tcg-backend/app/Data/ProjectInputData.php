<?php

namespace App\Data;

use App\Enums\ProjectStatus;
use Spatie\LaravelData\Attributes\Validation\Enum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class ProjectInputData extends Data
{
    public function __construct(
        protected readonly string        $name,
        protected readonly string        $clientName,
        #[WithCast(Enum::class, ProjectStatus::class)]
        protected readonly ProjectStatus $projectStatus = ProjectStatus::DRAFT,
    )
    {
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'client_name' => $this->clientName,
            'status' => $this->projectStatus->value,
        ];
    }
}
