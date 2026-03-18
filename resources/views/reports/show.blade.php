<x-layouts.app title="{{ $report->title }}">
    <x-slot:header>Report Detail</x-slot:header>

    <div class="max-w-3xl">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-lg font-semibold">{{ $report->title }}</h2>
                <p class="text-xs text-secondary">{{ ucfirst($report->type) }} &middot; {{ $report->created_at->format('d M Y') }}</p>
            </div>
            <div class="flex items-center gap-2">
                <x-status-badge :status="$report->status" />
                @if(in_array($report->status, ['draft', 'revision_needed']) && auth()->id() === $student->user_id)
                    <x-button href="{{ route('reports.edit', [$student, $report]) }}" variant="secondary" size="sm">Edit</x-button>
                @endif
            </div>
        </div>

        <div class="space-y-4">
            <x-card title="Content">
                <div class="text-sm text-secondary leading-relaxed whitespace-pre-wrap">{{ $report->content }}</div>
            </x-card>

            @if($report->achievements)
                <x-card title="Key Achievements">
                    <div class="text-sm text-secondary leading-relaxed whitespace-pre-wrap">{{ $report->achievements }}</div>
                </x-card>
            @endif

            @if($report->challenges)
                <x-card title="Challenges">
                    <div class="text-sm text-secondary leading-relaxed whitespace-pre-wrap">{{ $report->challenges }}</div>
                </x-card>
            @endif

            @if($report->next_steps)
                <x-card title="Next Steps">
                    <div class="text-sm text-secondary leading-relaxed whitespace-pre-wrap">{{ $report->next_steps }}</div>
                </x-card>
            @endif

            {{-- Supervisor feedback --}}
            @if($report->supervisor_feedback)
                <x-card title="Supervisor Feedback">
                    <div class="bg-amber-50 border border-amber-100 rounded-lg p-3 text-sm">
                        <p class="font-medium text-amber-800 text-xs mb-1">{{ $report->reviewer?->name }} &middot; {{ $report->reviewed_at?->format('d M Y') }}</p>
                        <div class="text-amber-900 whitespace-pre-wrap">{{ $report->supervisor_feedback }}</div>
                    </div>
                </x-card>
            @endif

            {{-- Review form (for supervisors) --}}
            @if($report->status === 'submitted' && (auth()->id() === $student->supervisor_id || auth()->id() === $student->cosupervisor_id || auth()->user()->isAdmin()))
                <x-card title="Review">
                    <form method="POST" action="{{ route('reports.review', [$student, $report]) }}" class="space-y-4">
                        @csrf
                        <x-textarea name="supervisor_feedback" label="Feedback" required rows="4" placeholder="Provide your feedback..." />
                        <div class="flex items-center gap-3">
                            <x-button type="submit" name="decision" value="accepted" variant="accent">Accept</x-button>
                            <x-button type="submit" name="decision" value="revision_needed" variant="secondary">Request Revision</x-button>
                        </div>
                    </form>
                </x-card>
            @endif
        </div>
    </div>
</x-layouts.app>
