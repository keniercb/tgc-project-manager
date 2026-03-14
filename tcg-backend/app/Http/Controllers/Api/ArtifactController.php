<?php

namespace App\Http\Controllers\Api;

use App\Data\ArtifactInputData;
use App\Enums\ArtifactState;
use App\Enums\ArtifactType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateArtifactRequest;
use App\Http\Requests\Api\SearchArtifactRequest;
use App\Http\Requests\Api\UpdateArtifactRequest;
use App\Http\Resources\ArtifactResource;
use App\Services\Contracts\ArtifactServiceInterface;
use App\Services\Contracts\GateValidationInterface;
use Illuminate\Http\JsonResponse;

class ArtifactController extends Controller
{

    public function __construct(protected ArtifactServiceInterface $artifactService, protected GateValidationInterface $gateValidation)
    {

    }

    public function index(SearchArtifactRequest $request)
    {
        return ArtifactResource::collection($this->artifactService->listArtifacts([
            'perPage' => $request->input('perPage') ?? null,
            'archivedAt' => $request->input('archivedAt') ?? null,
            'projectId' => $request->input('projectId'),
        ]));
    }

    public function create(CreateArtifactRequest $request): JsonResponse
    {
        $inputData = new ArtifactInputData(
            $request->input('projectId'),
            auth()->id(),
            ArtifactState::NOT_STARTED,
            ArtifactType::from($request->input('type')),
            $request->input('content')
        );
        $artifact = $this->artifactService->createArtifact($inputData);
        return response()->json([
            'artifact' => $artifact->toArray(),
            'message' => 'Artifact created successfully.'
        ]);
    }

    public function update(int $id, UpdateArtifactRequest $artifactRequest): JsonResponse
    {
        if ($artifactRequest->input('status') && $artifactRequest->input('status') == ArtifactState::COMPLETED->value) {
            $validation = $this->gateValidation->validateArtifactAsDone($this->artifactService->findArtifactById($id));
            if (!$validation['allowed']) {
                return response()->json([
                    'message' => 'Artifact can not be updated.',
                    'cause' => $validation['message']
                ], 500);
            }
        }
        $artifactData = new ArtifactInputData(
            $artifactRequest->input('projectId'),
            auth()->id(),
            ArtifactState::from($artifactRequest->input('status')),
            ArtifactType::from($artifactRequest->input('type')),
            $artifactRequest->input('content')
        );
        $this->artifactService->updateArtifact($id, $artifactData);
        return response()->json([]);
    }

    public function delete(int $id): JsonResponse
    {
        return response()->json([
            'message' => 'Artifact deleted successfully.'
        ]);
    }
}
