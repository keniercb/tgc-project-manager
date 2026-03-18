<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class AuditResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => $this->entity_type,
            'action' => $this->action,
            'date' => Carbon::parse($this->created_at)->toDateTimeString(),
            'userName' => User::query()->find($this->actor_user_id)->name
        ];
    }
}
