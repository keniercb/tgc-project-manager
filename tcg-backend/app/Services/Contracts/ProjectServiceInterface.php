<?php

namespace App\Services\Contracts;

use App\Data\ProjectInputData;
use App\Models\Project;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ProjectServiceInterface
{
    public function listProjects(array $filters): Collection|LengthAwarePaginator;

    public function createProject(ProjectInputData $inputData): Project;

    public function updateProject(int $projectId, ProjectInputData $inputData): Project;

    public function deleteProject(int $projectId): bool;

    public function archiveProject(int $projectId): bool;

    public function findProjectById(int $projectId): ?Project;
}
