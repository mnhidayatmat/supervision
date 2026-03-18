<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('programme_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supervisor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('cosupervisor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('research_title')->nullable();
            $table->text('research_abstract')->nullable();
            $table->string('intake')->nullable();
            $table->date('start_date')->nullable();
            $table->date('expected_completion')->nullable();
            $table->date('actual_completion')->nullable();
            $table->enum('status', ['pending', 'active', 'on_hold', 'completed', 'withdrawn'])->default('pending');
            $table->unsignedTinyInteger('overall_progress')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
