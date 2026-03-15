<?php

namespace App\Observers;

use App\Models\AuditEvent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class AuditObserver
{
    public function created(Model $model): void
    {
        $this->logEvent($model, 'created', [], $model->toArray());
    }

    public function updated(Model $model): void
    {
        if (!$model->isDirty()) {
            return;
        }
        $before = $model->getOriginal();
        $after = $model->getAttributes();
        $event = $model->isDirty('status') ? 'status changed' : 'updated';
        $this->logEvent($model, $event, $before, $after);
    }

    public function deleted(Model $model): void
    {
        $before = $model->getOriginal();
        $after = $model->getAttributes();
        $this->logEvent($model, 'deleted', $before, $after);
    }

    protected function logEvent(Model $model, string $action, array $before = [], array $after = []): void
    {
        $objectType = class_basename($model);
        $userId = auth()->id();
        AuditEvent::query()->create([
            'actor_user_id' => $userId,
            'entity_type' => $objectType,
            'entity_id' => $model->id,
            'action' => $action,
            'before_json' => $before ? json_encode($before) : null,
            'after_json' => $after ? json_encode($after) : null,
            'created_at' => Carbon::now(),
        ]);
    }
}
