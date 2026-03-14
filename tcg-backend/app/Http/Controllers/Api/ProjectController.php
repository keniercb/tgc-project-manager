<?php

namespace App\Http\Controllers\Api;

use App\Data\ProjectInputData;
use App\Data\ProjectOutputData;
use App\Enums\ProjectStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Services\Contracts\ProjectServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProjectController extends Controller
{

    public function __construct(protected ProjectServiceInterface $projectService)
    {

    }

    public function index(Request $request): AnonymousResourceCollection
    {
        return ProjectResource::collection($this->projectService->listProjects([
            'search' => $request->input('search') ?? null,
            'perPage' => $request->input('perPage') ?? null,
            'status' => $request->input('status') ?? null,
            'archivedAt' => $request->input('archivedAt') ?? null,
        ]));
    }

    public function list(Request $request): JsonResponse
    {

    }

    public function create(CreateProjectRequest $request): JsonResponse
    {
        $projectData = new ProjectInputData($request->input('name'), $request->input('clientName'),
            ProjectStatus::from($request->input('status')));
        $project = $this->projectService->createProject($projectData);
        return response()->json([
            'project' => $project,
            'message' => 'Project created successfully'
        ]);
    }

    public function update(int $id, CreateProjectRequest $request): JsonResponse
    {
        $projectData = new ProjectInputData($request->input('name'), $request->input('clientName'), ProjectStatus::from($request->input('status')));
        $project = $this->projectService->updateProject($id, $projectData);
        return response()->json([
                'project' => $project,
                'message' => 'Project updated successfully',
            ]
        );
    }

    public function archive(int $id): JsonResponse
    {
        $deleted = $this->projectService->archiveProject($id);
        return response()->json([
            'message' => $deleted ? 'Project deleted successfully' : 'Project not found'
        ], $deleted ? 200 : 404);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json([
            'project' => $id !== 0 ? (new ProjectOutputData(0, '', '', ''))->fromModel($this->projectService->findProjectById($id))->toArray() : null
        ]);
    }
}
