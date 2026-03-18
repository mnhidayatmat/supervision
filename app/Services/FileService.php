<?php

namespace App\Services;

use App\Models\File;
use App\Models\Folder;
use App\Models\Student;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileService
{
    public function createDefaultFolders(Student $student): void
    {
        $categories = ['proposal', 'reports', 'thesis', 'simulation', 'data', 'images', 'references'];
        $programme = $student->programme->slug ?? 'general';

        foreach ($categories as $category) {
            Folder::create([
                'student_id' => $student->id,
                'name' => ucfirst($category),
                'path' => "{$programme}/{$student->id}/{$category}",
                'category' => $category,
            ]);
        }
    }

    public function upload(UploadedFile $uploadedFile, Student $student, int $userId, ?int $folderId = null, ?string $description = null): File
    {
        $folder = $folderId ? Folder::find($folderId) : null;
        $basePath = $folder ? $folder->path : "files/{$student->id}";
        $filename = Str::uuid() . '.' . $uploadedFile->getClientOriginalExtension();
        $disk = config('filesystems.default', 'local');

        $path = $uploadedFile->storeAs($basePath, $filename, $disk);

        return File::create([
            'student_id' => $student->id,
            'folder_id' => $folderId,
            'uploaded_by' => $userId,
            'name' => $filename,
            'original_name' => $uploadedFile->getClientOriginalName(),
            'mime_type' => $uploadedFile->getMimeType(),
            'size' => $uploadedFile->getSize(),
            'disk' => $disk,
            'path' => $path,
            'description' => $description,
        ]);
    }

    public function uploadNewVersion(UploadedFile $uploadedFile, File $parentFile, int $userId): File
    {
        // Mark old as not latest
        $parentFile->update(['is_latest' => false]);

        $basePath = dirname($parentFile->path);
        $filename = Str::uuid() . '.' . $uploadedFile->getClientOriginalExtension();
        $path = $uploadedFile->storeAs($basePath, $filename, $parentFile->disk);

        return File::create([
            'student_id' => $parentFile->student_id,
            'folder_id' => $parentFile->folder_id,
            'uploaded_by' => $userId,
            'name' => $filename,
            'original_name' => $uploadedFile->getClientOriginalName(),
            'mime_type' => $uploadedFile->getMimeType(),
            'size' => $uploadedFile->getSize(),
            'disk' => $parentFile->disk,
            'path' => $path,
            'version' => $parentFile->version + 1,
            'parent_file_id' => $parentFile->parent_file_id ?? $parentFile->id,
            'is_latest' => true,
        ]);
    }

    public function delete(File $file): void
    {
        Storage::disk($file->disk)->delete($file->path);
        $file->delete();
    }
}
