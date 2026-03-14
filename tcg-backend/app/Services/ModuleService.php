<?php

namespace App\Services;

use App\Data\ModuleInputData;
use App\Models\Module;
use App\Repositories\Contracts\ModuleRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ModuleService implements Contracts\ModuleServiceInterface
{

    public function __construct(public ModuleRepositoryInterface $moduleRepository)
    {

    }

    public function getAllModules(array $filters = []): Collection|LengthAwarePaginator
    {
        if (isset($filters['perPage'])) {
            return $this->moduleRepository->paginate($filters, $filters['perPage']);
        }
        return $this->moduleRepository->all($filters);
    }

    public function createModule(ModuleInputData $inputData): Module
    {
        $data = $inputData->toArray();
        $data['created_at'] = Carbon::now();
        $data['created_by'] = auth()->user()->name;
        return $this->moduleRepository->create($data);
    }

    public function updateModule(int $id, array $data): bool
    {
        return $this->moduleRepository->update($id, $data);
    }

    public function validateModule(int $id): bool
    {
        return $this->moduleRepository->validate($id);
    }

    public function getModule(string $id): Module
    {
        return $this->moduleRepository->find($id);
    }
}
