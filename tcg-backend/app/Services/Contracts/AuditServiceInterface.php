<?php

namespace App\Services\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;

interface AuditServiceInterface
{
    public function list($params = array()) : LengthAwarePaginator;
}
