<?php

namespace App\Services;

use App\Repositories\Contracts\AuditRepositoryInterface;
use App\Services\Contracts\AuditServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class AuditService implements Contracts\AuditServiceInterface
{

    public function __construct(protected AuditRepositoryInterface $auditRepository)
    {

    }

    public function list($params = array()): LengthAwarePaginator
    {
        return $this->auditRepository->getPaginated($params);
    }
}
