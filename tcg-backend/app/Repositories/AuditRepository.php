<?php

namespace App\Repositories;

use App\Models\AuditEvent;
use App\Repositories\Contracts\AuditRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class AuditRepository implements Contracts\AuditRepositoryInterface
{

    private function getQuery($params = array()): Builder
    {
        $query = AuditEvent::query();
        if (isset($params['authorId'])) {
            $query->where('actor_user_id', '=', $params['authorId']);
        }
        if (isset($params['objectId'])) {
            $query->where('entity_id', '=', $params['objectId']);
        }
        if (isset($params['objectType'])) {
            $query->where('entity_type', '=', $params['objectType']);
        }
        return $query;
    }

    public function getPaginated($params = array()): LengthAwarePaginator
    {
        return $this->getQuery($params)->paginate($params['perPage'], '*', 'page', $params['page']);
    }
}
