<?php

namespace App\Services\Contracts;

use App\Data\ArtifactInputData;
use App\Models\Artifact;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ArtifactServiceInterface
{
    public function listArtifacts(array $filter = []): Collection|LengthAwarePaginator;

    public function createArtifact(ArtifactInputData $data): Artifact;

    public function updateArtifact(int $artifactId, ArtifactInputData $data): Artifact;

    public function deleteArtifact(int $artifactId): bool;

    public function findArtifactById(int $artifactId): Artifact;
}
