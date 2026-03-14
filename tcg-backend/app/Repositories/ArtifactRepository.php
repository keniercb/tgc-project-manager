<?php

namespace App\Repositories;

use App\Models\Artifact;
use App\Repositories\Contracts\ArtifactRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ArtifactRepository implements Contracts\ArtifactRepositoryInterface
{

    public function createArtifact(array $data): Artifact
    {
        return Artifact::query()->create($data);
    }

    /**
     * @throws Exception
     */
    public function updateArtifact(int $id, array $data): Artifact
    {
        $artifact = Artifact::query()->findOrFail($id);
        if ($artifact) {
            $artifact->update($data);
            return $artifact;
        }
        throw  new Exception("Artifact not found");
    }

    /**
     * @throws Exception
     */
    public function deleteArtifact(int $id): bool
    {
        $artifact = Artifact::query()->findOrFail($id);
        if ($artifact) {
            return $artifact->delete();
        }
        throw  new Exception("Artifact not found");
    }

    public function getArtifact(int $id): Artifact
    {
        return Artifact::query()->findOrFail($id);
    }

    private function getQuery(array $filters): Builder
    {
        $query = Artifact::query();
        if (isset($filters['projectId'])) {
            $query->where('project_id', '=', $filters['projectId']);
        }
        if (isset($filters['ownerId'])) {
            $query->where('owner_id', '=', $filters['ownerId']);
        }
        if (isset($filters['status'])) {
            $query->where('status', '=', $filters['status']);
        }
        if (isset($filters['completedAt'])) {
            $query->whereDate('completed_at', $filters['completedAt']);
        } else {
            if (isset($filters['completedAtSince'])) {
                $query->where('completed_at', '>', $filters['completedAtSince']);
            }
            if (isset($filters['completedAtUntil'])) {
                $query->where('completed_at', '<', $filters['completedAtUntil']);
            }
        }
        return $query;
    }

    public function getAll(array $filter = []): Collection
    {
        return $this->getQuery($filter)->get();
    }

    public function getPaginated(array $filter = [], $perPage = 10): LengthAwarePaginator
    {
        return $this->getQuery($filter)->paginate($perPage);
    }
}
