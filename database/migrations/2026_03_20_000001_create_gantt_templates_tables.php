<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gantt_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['visual', 'content', 'both'])->default('both');
            $table->enum('data_source', ['research', 'custom', 'both'])->default('both');
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->json('visual_config')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'is_active']);
        });

        Schema::create('gantt_template_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gantt_template_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('gantt_template_items')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('item_type', ['activity', 'milestone', 'phase'])->default('activity');
            $table->integer('start_offset')->nullable();
            $table->integer('duration_days')->nullable();
            $table->unsignedTinyInteger('progress')->default(0);
            $table->string('color')->nullable();
            $table->integer('sort_order')->default(0);
            $table->json('dependencies')->nullable();
            $table->timestamps();

            $table->index(['gantt_template_id', 'sort_order']);
            $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gantt_template_items');
        Schema::dropIfExists('gantt_templates');
    }
};
