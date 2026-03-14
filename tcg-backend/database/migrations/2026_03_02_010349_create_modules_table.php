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
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('domain');
            $table->string('name');
            $table->integer('project_id');
            $table->enum('status', ['draft', 'validated', 'ready_for_build'])->default('draft');
            $table->string('created_by');

            $table->string('data_structure')->nullable();
            $table->string('logic_rules')->nullable();
            $table->string('objective')->nullable();
            $table->string('responsibility')->nullable();
            $table->string('failure_scenarios')->nullable();
            $table->string('audit_trail_requirements')->nullable();
            $table->string('version_note')->nullable();
            $table->json('outputs')->nullable();
            $table->json('dependencies')->nullable();
            $table->json('inputs')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
