<?php

namespace App\Data;

use App\Enums\ProjectStatus;
use App\Models\Project;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;

class ProjectOutputData extends Data
{
    public function __construct(
        public int    $id = 0,
        public string $name = '',
        public string $clientName = '',
        public string $status = '',
        public string $createdAt = '',
        public int    $artifacts = 0,
        public int    $modules = 0,

    )
    {
    }

    public function fromModel(Project $model): ProjectOutputData
    {
        $this->id = $model->id;
        $this->name = $model->name;
        $this->status = ProjectStatus::status(ProjectStatus::from($model->status));
        $this->clientName = $model->client_name;
        $this->createdAt = Carbon::parse($model->created_at)->toDateString();
        $this->artifacts = $model->artifacts()->count();
        $this->modules = $model->modules()->count();
        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => $this->status,
            'client' => $this->clientName,
            'createdAt' => $this->createdAt,
            'artifactsCount' => $this->artifacts,
            'modulesCount' => $this->modules,
        ];
    }
}
