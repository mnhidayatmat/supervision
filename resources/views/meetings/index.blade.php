<x-layouts.app title="Meetings">
    <x-slot:header>Meetings</x-slot:header>

    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-secondary">{{ $student->user->name }}'s meetings</p>
        <x-button href="{{ route('meetings.create', $student) }}" variant="accent" size="sm">+ Schedule Meeting</x-button>
    </div>

    <div class="space-y-3">
        @forelse($meetings as $meeting)
            <a href="{{ route('meetings.show', [$student, $meeting]) }}" class="block">
                <x-card class="hover:shadow-sm transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-semibold">{{ $meeting->title }}</h3>
                            <p class="text-xs text-secondary mt-0.5">
                                {{ $meeting->scheduled_at->format('d M Y, h:i A') }}
                                &middot; {{ ucfirst(str_replace('_', ' ', $meeting->type)) }}
                                &middot; {{ ucfirst(str_replace('_', ' ', $meeting->mode)) }}
                                @if($meeting->duration_minutes) &middot; {{ $meeting->duration_minutes }}min @endif
                            </p>
                            @if($meeting->actionItems->count())
                                <p class="text-xs text-secondary mt-1">
                                    {{ $meeting->actionItems->where('is_completed', true)->count() }}/{{ $meeting->actionItems->count() }} action items done
                                </p>
                            @endif
                        </div>
                        <x-status-badge :status="$meeting->status" />
                    </div>
                </x-card>
            </a>
        @empty
            <x-card>
                <p class="text-sm text-secondary text-center py-4">No meetings yet.</p>
            </x-card>
        @endforelse
    </div>

    <div class="mt-4">{{ $meetings->links() }}</div>
</x-layouts.app>
