<?php

namespace App\Repositories\Contracts;

use App\Models\Artifact;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ArtifactRepositoryInterface
{
    public function createArtifact(array $data): Artifact;

    public function updateArtifact(int $id, array $data): Artifact;

    public function deleteArtifact(int $id): bool;

    public function getArtifact(int $id): Artifact;

    public function getAll(array $filter = []) : Collection;
    public function getPaginated(array $filter = [], $perPage = 10) : LengthAwarePaginator;
}
