<?php

namespace Database\Factories;

use App\Enums\ArtifactState;
use App\Enums\ArtifactType;
use App\Models\Artifact;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Artifact>
 */
class ArtifactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_id' => null,
            'type' => $this->faker->randomElement(ArtifactType::values()),
            'status' => ArtifactState::NOT_STARTED,
            'owner_id' => null,
            'content' => "",
            'completed_at' => null,
        ];
    }

    public function done(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ArtifactState::COMPLETED->value,
            'completed_at' => now(),
        ]);
    }
}
