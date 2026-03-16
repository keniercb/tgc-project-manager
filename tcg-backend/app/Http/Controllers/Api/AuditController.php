<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\FilterAuditRequest;
use App\Http\Resources\AuditResource;
use App\Services\Contracts\AuditServiceInterface;

class AuditController extends Controller
{
    public function __construct(protected AuditServiceInterface $auditService)
    {

    }

    public function list(FilterAuditRequest $request)
    {
        return AuditResource::collection($this->auditService->list($request->validated()));
    }
}
