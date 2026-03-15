<?php

namespace App\Services;

use App\Enums\ArtifactState;
use App\Enums\ArtifactType;
use App\Enums\ProjectStatus;
use App\Models\Artifact;
use App\Models\Module;
use App\Models\Project;
use App\Services\Contracts\GateValidationInterface;

class GateValidationService implements Contracts\GateValidationInterface
{

    public function validateArtifactAsDone(Artifact $artifact): array
    {
        if ($artifact->status === ArtifactState::COMPLETED->value) {
            return [
                'allowed' => true,
                'message' => 'Artifacts are completed'
            ];
        }
        return match ($artifact->type) {
            ArtifactType::DB->value => $this->validateArtifactCompleted($artifact, ArtifactType::BP),
            ArtifactType::MM->value => $this->validateArtifactCompleted($artifact, ArtifactType::DB),
            ArtifactType::SA->value => $this->checkValidatedModules($artifact, ArtifactType::SA),
            default => [
                'allowed' => true,
                'message' => ''
            ],
        };
    }

    public function validateProjectTransaction(Project $project, ProjectStatus $newStatus): array
    {
        if ($project->status == ProjectStatus::DISCOVERY->value && $newStatus == ProjectStatus::EXECUTION) {
            $artifactsTypes = [
                ArtifactType::SA, ArtifactType::DB, ArtifactType::BP, ArtifactType::MM
            ];
            $artifactsDescriptions = array_map(function ($item) {
                return ArtifactType::description($item);
            }, $artifactsTypes);
            $artifactsCompleted = Artifact::query()->whereIn('type', [ArtifactType::SA->value, ArtifactType::DB->value, ArtifactType::BP->value, ArtifactType::MM->value])
                ->where('project_id', $project->id)->where('status', '<>', ArtifactState::COMPLETED->value)->count();
            if ($artifactsCompleted > 0) {
                return [
                    'allowed' => false,
                    'message' => 'Project status could not be updated before' . implode(', ', $artifactsDescriptions) . ' artifacts are completed.'
                ];
            }
        }
        return [
            'allowed' => true,
            'message' => ''
        ];
    }


    private function validateArtifactCompleted(Artifact $artifact, ArtifactType $type): array
    {
        $nestedArtifact = $artifact->project->artifacts()->where('type', '=', $type->value)->first();
        $typeFrom = ArtifactType::description(ArtifactType::from($artifact->type));
        $typeTo = ArtifactType::description($type);
        if (!$nestedArtifact || $nestedArtifact->status !== ArtifactState::COMPLETED->value) {
            return [
                'allowed' => false,
                'message' => "{$typeTo} must be completed before {$typeFrom} can be marked as completed."
            ];
        }
        return [
            'allowed' => true,
            'message' => ''
        ];
    }

    private function checkValidatedModules(Artifact $artifact, ArtifactType $type): array
    {
        $validatedModulesCount = $artifact->project->modules()->
        where('status', '=', 'validated')->count();
        if ($validatedModulesCount < config('gate.min_validated_modules')) {
            return [
                'allowed' => false,
                'message' => ArtifactType::description($type) . " requires at least " . config('gate.min_validated_modules') . " validated modules. Currently {$validatedModulesCount} modules validated."
            ];
        }
        return [
            'allowed' => true,
            'message' => ''
        ];
    }

    public function canValidateModule(Module $module): array
    {
        $errors = [];
        if (empty($module->objective)) {
            $errors[] = 'objective';
        }
        if (empty($module->responsibility)) {
            $errors[] = 'responsibility';
        }
        if (empty($module->inputs)) {
            $errors[] = 'inputs';
        }
        $jsonErrors = [];
        if (!empty($module->inputs)) {
            $inputsDecoded = json_decode($module->inputs, true);
            if (count($inputsDecoded) == 0) {
                $jsonErrors[] = 'Inputs field must be a valid json object';
            }
        }
        if (empty($module->outputs)) {
            $errors[] = 'outputs';
        } else {
            $inputsDecoded = json_decode($module->outputs, true);
            if (count($inputsDecoded) == 0) {
                $jsonErrors[] = 'Outputs field must be a valid json object';
            }
        }
        if (count($errors)) {
            $jsonErrors[] = ucfirst(implode(',', $errors)) . (count($errors) > 1 ? ' fields are required' : ' field is required');
        }
        return [
            'allowed' => count($errors) == 0,
            'message' => implode('. ', $jsonErrors)
        ];
    }
}
