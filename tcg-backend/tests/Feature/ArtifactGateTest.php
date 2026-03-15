<?php

namespace Tests\Feature;

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

    private User $pmUser;
    private Project $project;

    protected function setUp(): void
    {
        parent::setUp();

        $this->pmUser = User::factory()->create();
        $pmRole = Role::query()->firstOrCreate(['name' => 'pm', 'guard_name' => 'api']);
        $pmRole->givePermissionTo(['artifacts.manage', 'modules.manage']);
        $this->pmUser->assignRole('pm');
        $this->project = Project::factory()->create([
            'status' => 'discovery',
            'created_by' => $this->pmUser->id,
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

        Sanctum::actingAs($this->pmUser);

        $response = $this->patchJson("/api/v1/artifacts/{$domainBreakdown->id}", [
            'status' => ArtifactState::COMPLETED->value,
        ]);

        $response->assertStatus(500)
            ->assertJsonValidationErrors(['status'])
            ->assertJson([
                'message' => 'Cannot complete this artifact. Big Picture must be completed first.',
            ]);

        $domainBreakdown->refresh();
        $this->assertEquals(ArtifactState::IN_PROGRESS, $domainBreakdown->status);
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

        Sanctum::actingAs($this->pmUser);

        $response = $this->patchJson("/api/v1/artifacts/{$domainBreakdown->id}", [
            'status' => ArtifactState::COMPLETED->value,
        ]);

        $response->assertStatus(200);

        $domainBreakdown->refresh();
        $this->assertEquals(ArtifactState::COMPLETED->value, $domainBreakdown->status);
        $this->assertNotNull($domainBreakdown->completed_at);
    }

    /** @test */
    public function gate_4_cannot_move_project_to_execution_if_required_artifacts_not_done()
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
                'status' => ArtifactState::IN_PROGRESS->value,
            ]);
        }

        Sanctum::actingAs($this->pmUser);

        $response = $this->patchJson("/api/v1/projects/{$this->project->id}", [
            'status' => 'execution',
        ]);
        $response->assertStatus(500)
            ->assertJsonStructure(['message', 'errors', 'blocking_artifacts'])
            ->assertJson([
                'message' => 'Cannot move project to execution. Complete required artifacts first.',
            ]);

        $this->project->refresh();
        $this->assertEquals('discovery', $this->project->status);
    }

    /** @test */
    public function gate_4_can_move_project_to_execution_if_all_required_artifacts_done()
    {
        // Arrange: Project in discovery
        $this->project->update(['status' => 'discovery']);

        // Create required artifacts as DONE
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

        Sanctum::actingAs($this->pmUser);

        $response = $this->patchJson("/api/v1/projects/{$this->project->id}", [
            'status' => 'execution',
        ]);

        $response->assertStatus(200);

        $this->project->refresh();
        $this->assertEquals('execution', $this->project->status);
    }
}
