<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Artifact extends Model
{

    protected $fillable = [
        'project_id',
        'status',
        'type',
        'owner_id',
        'completed_at',
        'content'
    ];

    public function getOwnerNameAttribute()
    {
        return $this->owner?->name;
    }

    public function getProjectNameAttribute()
    {
        return $this->project?->name;
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }
}
