<?php

namespace Tests\Feature;

use App\Enums\ProjectStatus;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Models\Artifact;
use App\Enums\ArtifactType;
use App\Enums\ArtifactState;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class ArtifactGateTest extends TestCase
{
    use RefreshDatabase;

    private Project $project;

    protected function setUp(): void
    {
        parent::setUp();
        $pmUser = User::factory()->create();
        $pmRole = Role::query()->firstOrCreate(['name' => 'admin', 'guard_name' => 'api']);
        $permission[] = Permission::query()->firstOrCreate(['name' => 'artifacts.manage', 'guard_name' => 'api']);
        $permission[] = Permission::query()->firstOrCreate(['name' => 'project.manage', 'guard_name' => 'api']);
        $pmRole->givePermissionTo($permission);
        $pmUser->assignRole('admin');
        Sanctum::actingAs($pmUser);
        $this->project = Project::factory()->create([
            'status' => 'discovery',
            'created_by' => $pmUser->id,
        ]);
    }

    /** @test */
    public function gate_1_cannot_mark_domain_breakdown_done_if_big_picture_not_done()
    {

        Artifact::factory()->create([
            'project_id' => $this->project->id,
            'type' => ArtifactType::BP->value,
            'status' => ArtifactState::IN_PROGRESS->value,
        ]);

        $domainBreakdown = Artifact::factory()->create([
            'project_id' => $this->project->id,
            'type' => ArtifactType::DB->value,
            'status' => ArtifactState::IN_PROGRESS->value,
        ]);
        $response = $this->put("/api/v1/artifact/update/{$domainBreakdown->id}", [
            'status' => ArtifactState::COMPLETED->value,
        ]);
        $response->assertStatus(500);
        $domainBreakdown->refresh();
        $this->assertEquals(ArtifactState::IN_PROGRESS->value, $domainBreakdown->status);
    }

    /** @test */
    public function gate_1_can_mark_domain_breakdown_done_if_big_picture_is_done()
    {
        Artifact::factory()->create([
            'project_id' => $this->project->id,
            'type' => ArtifactType::BP->value,
            'status' => ArtifactState::COMPLETED->value,
            'completed_at' => now(),
        ]);
        $domainBreakdown = Artifact::factory()->create([
            'project_id' => $this->project->id,
            'type' => ArtifactType::DB->value,
            'status' => ArtifactState::IN_PROGRESS->value,
        ]);
        $response = $this->put("/api/v1/artifact/update/{$domainBreakdown->id}", [
            'status' => ArtifactState::COMPLETED->value,
            'type' => ArtifactType::DB->value,
            'projectId' => $this->project->id,
            'content' => json_encode([])
        ]);
        $response->assertStatus(200);
        $domainBreakdown->refresh();
        $this->assertEquals(ArtifactState::COMPLETED->value, $domainBreakdown->status);
        $this->assertNotNull($domainBreakdown->completed_at);
    }

    /** @test */
    public function gate_4_cannot_move_project_to_execution_if_required_artifacts_not_done()
    {

        $this->project->update(['status' => ProjectStatus::DISCOVERY->value]);

        $requiredTypes = [
            ArtifactType::SA,
            ArtifactType::BP,
            ArtifactType::DB,
            ArtifactType::MM,
        ];
        foreach ($requiredTypes as $type) {
            Artifact::factory()->create([
                'project_id' => $this->project->id,
                'type' => $type->value,
                'status' => ArtifactState::IN_PROGRESS->value,
            ]);
        }
        $response = $this->put("/api/v1/projects/{$this->project->id}", [
            'status' => ProjectStatus::EXECUTION->value,
            'name'=>$this->project->name,
            'clientName' => $this->project->client_name,
        ]);
        $response->assertStatus(500);
        $this->project->refresh();
        $this->assertEquals(ProjectStatus::DISCOVERY->value, $this->project->status);
    }

    /** @test */
    public function gate_4_can_move_project_to_execution_if_all_required_artifacts_done()
    {

        $this->project->update(['status' => 'discovery']);

        $requiredTypes = [
            ArtifactType::SA,
            ArtifactType::BP,
            ArtifactType::DB,
            ArtifactType::MM,
        ];
        foreach ($requiredTypes as $type) {
            Artifact::factory()->create([
                'project_id' => $this->project->id,
                'type' => $type->value,
                'status' => ArtifactState::COMPLETED->value,
                'completed_at' => now(),
            ]);
        }
        $response = $this->put("/api/v1/projects/{$this->project->id}", [
            'status' => ProjectStatus::EXECUTION->value,
            'name'=> $this->project->name,
            'clientName'=> $this->project->client_name,
        ]);
        $response->assertStatus(200);
        $this->project->refresh();
        $this->assertEquals('execution', $this->project->status);
    }
}
