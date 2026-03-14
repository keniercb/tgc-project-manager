<?php

namespace App\Services\Contracts;

use App\Models\Artifact;
use App\Models\Module;
use App\Models\Project;

interface GateValidationInterface
{
    public function validateArtifactAsDone(Artifact $artifact): array;
    public function validateProjectTransaction(Project $project): array;

    public function canValidateModule(Module $module): array;
}
