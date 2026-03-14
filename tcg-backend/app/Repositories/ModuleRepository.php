<?php

namespace App\Repositories;

use App\Enums\ModuleStatus;
use App\Models\Module;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ModuleRepository implements Contracts\ModuleRepositoryInterface
{

    private function getQuery(array $params): Builder
    {
        $query = Module::query();
        if (isset($params['project_id'])) {
            $query->where('project_id', '=', $params['project_id']);
        }
        if (!empty($params['name'])) {
            $query->whereLike('name', '%' . $params['name'] . '%');
        }
        if (!empty($params['status'])) {
            $query->where('status', '=', $params['status']);
        }
        return $query;
    }

    public function all(array $params = []): Collection
    {
        return $this->getQuery($params)->get();
    }

    public function paginate(array $params = [], int $perPage = 10): LengthAwarePaginator
    {
        return $this->getQuery($params)->paginate($perPage);
    }

    public function find(int $id): Module
    {
        return Module::query()->findOrFail($id);
    }

    public function create(array $data): Module
    {
        return Module::query()->create($data);
    }

    public function update(int $id, array $data): bool
    {
        unset($data['id']);
        unset($data['projectId']);
        return Module::query()->where('id', $id)->update($data);
    }

    public function delete(int $id): bool
    {
        return Module::query()->findOrFail($id)->delete();
    }

    public function validate(int $id): bool
    {
        Module::findOrFail($id)->update(['status' => ModuleStatus::VALID]);
        return true;
    }
}
