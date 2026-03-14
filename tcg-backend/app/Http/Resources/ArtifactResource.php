<?php

namespace App\Http\Resources;

use App\Enums\ArtifactState;
use App\Enums\ArtifactType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class ArtifactResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'owner' => $this->ownerName,
            'project' => $this->projectName,
            'type' => ArtifactType::from($this->type),
            'typeDescription' => ArtifactType::description(ArtifactType::from($this->type)),
            'content' => $this->content,
            'status' => $this->status,
            'completedAt' => $this->completed_at,
            'statusDescription' => ArtifactState::description(ArtifactState::from($this->status))
        ];
    }
}
