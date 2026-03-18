<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Journey templates (admin-defined per programme)
        Schema::create('journey_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('programme_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Template stages
        Schema::create('template_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journey_template_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->integer('duration_weeks')->nullable();
            $table->timestamps();
        });

        // Template milestones
        Schema::create('template_milestones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_stage_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->integer('week_offset')->nullable();
            $table->timestamps();
        });

        // Student's actual research journey (instantiated from template)
        Schema::create('research_journeys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('journey_template_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->unsignedTinyInteger('progress')->default(0);
            $table->enum('status', ['not_started', 'in_progress', 'completed'])->default('not_started');
            $table->timestamps();
        });

        // Student's journey stages
        Schema::create('stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('research_journey_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', ['not_started', 'in_progress', 'completed'])->default('not_started');
            $table->unsignedTinyInteger('progress')->default(0);
            $table->timestamps();
        });

        // Student's milestones within stages
        Schema::create('milestones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stage_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->date('due_date')->nullable();
            $table->date('completed_at')->nullable();
            $table->enum('status', ['not_started', 'in_progress', 'completed'])->default('not_started');
            $table->unsignedTinyInteger('progress')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('milestones');
        Schema::dropIfExists('stages');
        Schema::dropIfExists('research_journeys');
        Schema::dropIfExists('template_milestones');
        Schema::dropIfExists('template_stages');
        Schema::dropIfExists('journey_templates');
    }
};
