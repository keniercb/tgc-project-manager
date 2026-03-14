<?php

namespace App\Repositories\Contracts;

use App\Models\Project;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ProjectRepositoryInterface
{
    public function all(array $filters = []): Collection;

    public function paginate(array $filters = [], $perPage = 10): LengthAwarePaginator;

    public function findProject($id): ?Project;

    public function createProject(array $data): Project;

    public function updateProject(int $id, array $data): Project;

    public function deleteProject(int $id): bool;

    public function archiveProject(int $id, User $user): bool;
}
