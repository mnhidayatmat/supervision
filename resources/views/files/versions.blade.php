<x-layouts.app title="File Versions">
    <x-slot:header>File Versions</x-slot:header>

    <div class="max-w-2xl">
        <div class="mb-6">
            <h2 class="text-lg font-semibold">{{ $file->original_name }}</h2>
            <p class="text-xs text-secondary">All versions of this file</p>
        </div>

        <div class="space-y-3">
            @foreach($versions as $version)
                <x-card>
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                                <p class="text-sm font-medium">Version {{ $version->version }}</p>
                                @if($version->is_latest)
                                    <x-badge color="green">Latest</x-badge>
                                @endif
                            </div>
                            <p class="text-xs text-secondary mt-0.5">
                                {{ $version->original_name }} &middot; {{ $version->sizeForHumans() }} &middot;
                                Uploaded {{ $version->created_at->format('d M Y, h:i A') }} by {{ $version->uploader->name }}
                            </p>
                        </div>
                        <a href="{{ route('files.download', [$student, $version]) }}" class="text-xs text-accent hover:underline">Download</a>
                    </div>
                </x-card>
            @endforeach
        </div>

        {{-- Upload new version --}}
        <x-card title="Upload New Version" class="mt-6">
            <form method="POST" action="{{ route('files.upload-version', [$student, $file]) }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <input type="file" name="file" required class="w-full text-sm text-secondary file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-accent/10 file:text-accent hover:file:bg-accent/20">
                </div>
                <x-button type="submit" variant="accent" size="sm">Upload Version {{ $versions->first()->version + 1 }}</x-button>
            </form>
        </x-card>

        <div class="mt-4">
            <x-button href="{{ route('files.index', [$student, 'folder' => $file->folder_id]) }}" variant="secondary" size="sm">Back to Files</x-button>
        </div>
    </div>
</x-layouts.app>
