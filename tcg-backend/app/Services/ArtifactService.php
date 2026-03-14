<?php

namespace App\Services;

use App\Data\ArtifactInputData;
use App\Enums\ArtifactState;
use App\Models\Artifact;
use App\Repositories\ArtifactRepository;
use App\Repositories\Contracts\ArtifactRepositoryInterface;
use App\Services\Contracts\ArtifactServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ArtifactService implements Contracts\ArtifactServiceInterface
{

    public function __construct(protected ArtifactRepositoryInterface $artifactRepository)
    {

    }

    public function listArtifacts(array $filter = []): Collection|LengthAwarePaginator
    {
        if (isset($filter['perPage'])) {
            return $this->artifactRepository->getPaginated($filter, $filter['perPage']);
        }
        return $this->artifactRepository->getAll($filter);
    }

    public function createArtifact(ArtifactInputData $data): Artifact
    {
        $input = $data->toArray();
        $input['created_at'] = Carbon::now();
        return $this->artifactRepository->createArtifact($input);
    }

    public function updateArtifact(int $artifactId, ArtifactInputData $data): Artifact
    {
        $input = $data->toArray();
        $input['updated_at'] = Carbon::now();
        if ($data->artifactState == ArtifactState::COMPLETED) {
            $input['completed_at'] = Carbon::now();
        }
        return $this->artifactRepository->updateArtifact($artifactId, $input);
    }

    public function deleteArtifact(int $artifactId): bool
    {
        return $this->artifactRepository->deleteArtifact($artifactId);
    }

    public function findArtifactById(int $artifactId): Artifact
    {
        return $this->artifactRepository->getArtifact($artifactId);
    }
}
