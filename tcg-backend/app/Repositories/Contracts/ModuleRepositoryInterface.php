<?php

namespace App\Repositories\Contracts;

use App\Models\Module;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ModuleRepositoryInterface
{
    public function all(array $params = []): Collection;

    public function paginate(array $params = [], int $perPage = 10): LengthAwarePaginator;

    public function find(int $id): Module;

    public function create(array $data): Module;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function validate(int $id): bool;
}
