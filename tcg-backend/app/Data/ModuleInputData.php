<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class ModuleInputData extends Data
{
    public function __construct(
        public string $name,
        public string $objective,
        public string $domain,
        public int    $projectId,
        public array  $fields,
    )
    {
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'objective' => $this->objective,
            'domain' => $this->domain,
            'project_id' => $this->projectId,
            'data_structure' => $this->fields['dataStructure'] ?? null,
            'logic_rules' => $this->fields['logicRules'] ?? null,
            'failure_scenarios' => $this->fields['failureScenarios'] ?? null,
            'responsibility' => $this->fields['responsibility'] ?? null,
            'audit_trail_requirements' => $this->fields['auditTrailRequirements'] ?? null,
            'version_note' => $this->fields['versionNote'] ?? null,
            'inputs' => $this->fields['inputs'] ?? '{}',
            'outputs' => $this->fields['outputs'] ?? '{}',
            'dependencies' => $this->fields['dependencies'] ?? '{}',
        ];
    }
}
