<?php

namespace App\Http\Resources;

use App\Enums\ModuleStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ModuleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => $this->status,
            'statusDescription' => ModuleStatus::description(ModuleStatus::from($this->status)),
            'domain' => $this->domain,
            'objective' => $this->objective,
            'dataStructure' => $this->data_structure,
            'logicRules' => $this->logic_rules,
            'responsibility' => $this->responsibility,
            'failureScenarios' => $this->failure_scenarios,
            'auditTrailRequirements' => $this->audit_trail_requirements,
            'versionNote' => $this->version_note,
            'outputs' => $this->outputs,
            'inputs' => $this->inputs,
            'dependencies' => $this->dependencies,
        ];
    }
}
