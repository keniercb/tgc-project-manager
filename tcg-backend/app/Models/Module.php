<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = [
        'name',
        'domain',
        'project_id',
        'status',
        'created_by',
        'objective',
        'data_structure',
        'logic_rules',
        'responsibility',
        'failure_scenarios',
        'audit_trail_requirements',
        'version_note',
        'outputs',
        'inputs',
        'dependencies'
    ];
}
