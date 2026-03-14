<?php

namespace App\Repositories;

use App\Exceptions\ProjectNotFoundException;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ProjectRepository implements Contracts\ProjectRepositoryInterface
{

    public function all(array $filters = []): Collection
    {
        return $this->getQuery($filters)->get();
    }

    private function getQuery(array $filters = []): Builder
    {
        $query = Project::query();
        if (!empty($filters)) {
            if (!empty($filters['search'])) {
                $query->search($filters['search']);
            } else {
                if (!empty($filters['name'])) {
                    $query->name($filters['name']);
                }
                if (!empty($filters['clientName'])) {
                    $query->clientName($filters['clientName']);
                }
            }
            if (!empty($filters['status'])) {
                $query->status($filters['status']);
            }
            if (!empty($filters['archivedAt'])) {
                $query->whereDate('deleted_at', '>=', Carbon::parse($filters['archivedAt']));
            } else {
                $query->whereNull('deleted_at');
            }
        }

        return $query;
    }

    public function paginate(array $filters = [], $perPage = 10): LengthAwarePaginator
    {
        return $this->getQuery($filters)->paginate($perPage);
    }

    public function findProject($id): ?Project
    {
        return Project::query()->find($id);
    }

    public function createProject(array $data): Project
    {
        return Project::query()->create($data);
    }

    /**
     * @throws ProjectNotFoundException
     */
    public function updateProject(int $id, array $data): Project
    {
        $project = Project::query()->find($id);
        if ($project) {
            $project->update($data);
            return $project;
        }
        throw new ProjectNotFoundException();
    }

    public function deleteProject(int $id): bool
    {
        $project = Project::query()->find($id);
        if ($project) {
            $project->delete();
            return true;
        }
        return false;
    }

    public function archiveProject(int $id, User $user): bool
    {
        $project = Project::query()->find($id);
        if ($project) {
            $project->deleted_at = Carbon::now();
            $project->deleted_by = $user->name;
            $project->save();
            return true;
        }
        return false;
    }
}
