<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'name',
        'client_name',
        'created_by',
        'status',
    ];

    public function artifacts(): HasMany
    {
        return $this->hasMany(Artifact::class);
    }

    public function modules(): HasMany
    {
        return $this->hasMany(Module::class);
    }

    public function scopeSearch($query, string $keyword)
    {
        return $query->where('name', 'LIKE', "%{$keyword}%");
    }

    public function scopeClientName($query, string $keyword)
    {
        return $query->where('client_name', 'LIKE', "%{$keyword}%");
    }

    public function scopeStatus($query, string $status)
    {
        return $query->where('status', '=', $status);
    }
}
