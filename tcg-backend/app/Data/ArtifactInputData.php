<?php

namespace App\Data;

use App\Enums\ArtifactState;
use App\Enums\ArtifactType;
use Spatie\LaravelData\Data;

class ArtifactInputData extends Data
{
    public function __construct(
        public int           $projectId,
        public int           $ownerId,
        public ArtifactState $artifactState = ArtifactState::NOT_STARTED,
        public ArtifactType  $artifactType = ArtifactType::SA,
        public string        $content = '{}',
    )
    {
    }

    public function toArray(): array
    {
        return [
            'project_id' => $this->projectId,
            'owner_id' => $this->ownerId,
            'status' => $this->artifactState->value,
            'type' => $this->artifactType->value,
            'content' => $this->content,
        ];

    }
}
