<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Folders for organizing files
        Schema::create('folders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('folders')->cascadeOnDelete();
            $table->string('name');
            $table->string('path');
            $table->enum('category', [
                'proposal', 'reports', 'thesis', 'simulation', 'data', 'images', 'references', 'other'
            ])->nullable();
            $table->timestamps();

            $table->index(['student_id', 'parent_id']);
        });

        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('folder_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->string('original_name');
            $table->string('mime_type');
            $table->unsignedBigInteger('size');
            $table->string('disk')->default('local');
            $table->string('path');
            $table->text('description')->nullable();
            $table->unsignedInteger('version')->default(1);
            $table->foreignId('parent_file_id')->nullable()->constrained('files')->nullOnDelete();
            $table->boolean('is_latest')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['student_id', 'folder_id']);
            $table->index('is_latest');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('files');
        Schema::dropIfExists('folders');
    }
};
