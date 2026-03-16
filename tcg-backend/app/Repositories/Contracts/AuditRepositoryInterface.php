<?php

namespace App\Repositories\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;

interface AuditRepositoryInterface
{
    public function getPaginated($params = array()) : LengthAwarePaginator;
}
