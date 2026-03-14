<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('artifacts', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['not started', 'in progress', 'blocked', 'done'])->default('not started');
            $table->enum('type', ['strategic_alignment', 'big_picture', 'domain_breakdown', 'module_matrix',
                'module_engineering', 'system_architecture', 'phase_scope'
            ])->default('strategic_alignment');
            $table->bigInteger('owner_id')->unsigned()->nullable();
            $table->bigInteger('project_id')->unsigned();
            $table->string('content')->default('{}');
            $table->date('completed_at')->nullable();
            $table->timestamps();

            $table->foreign('owner_id')->references('id')->on('users');
            $table->foreign('project_id')->references('id')->on('projects');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artifacts');
    }
};
