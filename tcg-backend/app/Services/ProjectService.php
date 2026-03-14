<?php

namespace App\Services;

use App\Data\ProjectInputData;
use App\Models\Project;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use App\Services\Contracts\ProjectServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ProjectService implements Contracts\ProjectServiceInterface
{

    public function __construct(protected ProjectRepositoryInterface $projectRepository)
    {

    }

    public function listProjects(array $filters): Collection|LengthAwarePaginator
    {
        if (isset($filters['perPage'])) {
            return $this->projectRepository->paginate($filters, $filters['perPage']);
        }
        return $this->projectRepository->all($filters);
    }

    public function createProject(ProjectInputData $inputData): Project
    {
        $data = $inputData->toArray();
        $data['created_at'] = Carbon::now();
        $data['created_by'] = auth()->user()->name;
        return $this->projectRepository->createProject($data);
    }

    public function updateProject(int $projectId, ProjectInputData $inputData): Project
    {
        $data = $inputData->toArray();
        $data['updated_at'] = Carbon::now();
        $data['updated_by'] = auth()->user()->name;
        return $this->projectRepository->updateProject($projectId, $data);
    }

    public function deleteProject(int $projectId): bool
    {
        return $this->projectRepository->deleteProject($projectId);
    }

    public function archiveProject(int $projectId): bool
    {
        return $this->projectRepository->archiveProject($projectId, auth()->user());
    }

    public function findProjectById(int $projectId): ?Project
    {
        return $this->projectRepository->findProject($projectId);
    }
}
