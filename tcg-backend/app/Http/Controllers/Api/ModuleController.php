<?php

namespace App\Http\Controllers\Api;

use App\Data\ModuleInputData;
use App\Enums\ModuleStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\FilterModuleRequest;
use App\Http\Requests\Api\UpdateModuleRequest;
use App\Http\Resources\ModuleResource;
use App\Services\Contracts\GateValidationInterface;
use App\Services\Contracts\ModuleServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ModuleController extends Controller
{
    public function __construct(protected ModuleServiceInterface $moduleService, protected GateValidationInterface $validation)
    {

    }

    public function list(FilterModuleRequest $request): AnonymousResourceCollection
    {
        return ModuleResource::collection(
            $this->moduleService->getAllModules([
                'project_id' => $request->input('projectId'),
                'name' => $request->input('name') ?? '',
                'perPage' => $request->input('perPage') ?? null,
                'status' => $request->input('status') ?? '',
            ]));
    }

    public function create(Request $request): JsonResponse
    {
        $inputData = new ModuleInputData(
            $request->input('name'),
            $request->input('objective'),
            $request->input('domain'),
            $request->input('projectId'),
            $request->input('fields'),
        );
        return response()->json([
            'data' => $this->moduleService->createModule($inputData),
            'message' => 'Module created successfully.'
        ]);
    }

    public function update(UpdateModuleRequest $request, int $id): JsonResponse
    {
        return response()->json([
            'data' => $this->moduleService->updateModule($id, $request->all()),
            'message' => 'Module updated successfully.'
        ]);
    }

    public function validate(string $id)
    {
        $validated = $this->validation->canValidateModule($this->moduleService->getModule($id));
        if (!$validated['allowed']) {
            return response()->json([
                'message' => $validated['message']
            ], 522);
        }
        return response()->json([
            'data' => $this->moduleService->updateModule($id, [
                'status' => ModuleStatus::VALID,
            ]),
        ]);
    }

    public function deploy(string $id)
    {
        return response()->json([
            'data' => $this->moduleService->updateModule($id, [
                'status' => ModuleStatus::RFB,
            ]),
        ]);
    }
}
