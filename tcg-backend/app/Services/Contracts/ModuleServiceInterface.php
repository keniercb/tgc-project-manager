<?php

namespace App\Services\Contracts;

use App\Data\ModuleInputData;
use App\Models\Module;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ModuleServiceInterface
{
    public function getAllModules(array $filters = []): Collection|LengthAwarePaginator;

    public function createModule(ModuleInputData $inputData): Module;

    public function updateModule(int $id, array $data): bool;

    public function validateModule(int $id): bool;

    public function getModule(string $id): Module;
}
