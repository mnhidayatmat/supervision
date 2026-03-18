<x-layouts.app title="My Dashboard">
    <x-slot:header>Dashboard</x-slot:header>

    {{-- Overview --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-stat-card label="Programme" :value="$student->programme->code" />
        <x-stat-card label="Progress" :value="$student->overall_progress . '%'" />
        <x-stat-card label="Total Tasks" :value="$tasks->count()" />
        <x-stat-card label="Completed" :value="$tasks->where('status', 'completed')->count()" />
    </div>

    {{-- Research info --}}
    <x-card class="mb-6">
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <p class="text-xs text-secondary uppercase tracking-wide mb-1">Research Title</p>
                <p class="text-sm font-medium">{{ $student->research_title ?? 'Not assigned yet' }}</p>
            </div>
            <div>
                <p class="text-xs text-secondary uppercase tracking-wide mb-1">Supervisor</p>
                <p class="text-sm font-medium">{{ $student->supervisor?->name ?? 'Not assigned' }}</p>
                @if($student->cosupervisor)
                    <p class="text-xs text-secondary">Co-supervisor: {{ $student->cosupervisor->name }}</p>
                @endif
            </div>
            <div>
                <p class="text-xs text-secondary uppercase tracking-wide mb-1">Start Date</p>
                <p class="text-sm">{{ $student->start_date?->format('d M Y') ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs text-secondary uppercase tracking-wide mb-1">Expected Completion</p>
                <p class="text-sm">{{ $student->expected_completion?->format('d M Y') ?? '—' }}</p>
            </div>
        </div>
    </x-card>

    <div class="grid lg:grid-cols-2 gap-6">
        {{-- Upcoming tasks --}}
        <x-card title="Upcoming Tasks">
            @forelse($upcomingTasks as $task)
                <a href="{{ route('tasks.show', [$student, $task]) }}" class="flex items-center justify-between py-2.5 {{ !$loop->last ? 'border-b border-border' : '' }}">
                    <div>
                        <p class="text-sm font-medium">{{ $task->title }}</p>
                        <p class="text-xs text-secondary">Due {{ $task->due_date->format('d M') }}</p>
                    </div>
                    <x-status-badge :status="$task->status" />
                </a>
            @empty
                <p class="text-sm text-secondary">No upcoming tasks.</p>
            @endforelse
            <div class="mt-3 pt-3 border-t border-border">
                <a href="{{ route('tasks.index', $student) }}" class="text-xs text-accent hover:underline">View all tasks</a>
            </div>
        </x-card>

        {{-- Research journey --}}
        <x-card title="Research Journey">
            @forelse($student->researchJourneys as $journey)
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm font-medium">{{ $journey->name }}</p>
                        <x-status-badge :status="$journey->status" />
                    </div>
                    @foreach($journey->stages as $stage)
                        <div class="ml-2 mb-2">
                            <div class="flex items-center gap-2 mb-1">
                                <div class="w-2 h-2 rounded-full {{ $stage->status === 'completed' ? 'bg-green-500' : ($stage->status === 'in_progress' ? 'bg-accent' : 'bg-gray-300') }}"></div>
                                <p class="text-xs font-medium">{{ $stage->name }}</p>
                            </div>
                            @foreach($stage->milestones as $milestone)
                                <div class="ml-4 flex items-center gap-2 py-0.5">
                                    <div class="w-1.5 h-1.5 rounded-full {{ $milestone->status === 'completed' ? 'bg-green-400' : 'bg-gray-200' }}"></div>
                                    <span class="text-xs text-secondary">{{ $milestone->name }}</span>
                                    @if($milestone->due_date)
                                        <span class="text-[10px] text-secondary/60">{{ $milestone->due_date->format('d M') }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @empty
                <p class="text-sm text-secondary">No journey assigned yet.</p>
            @endforelse
        </x-card>

        {{-- Recent reports --}}
        <x-card title="Recent Reports">
            @forelse($recentReports as $report)
                <a href="{{ route('reports.show', [$student, $report]) }}" class="block py-2.5 {{ !$loop->last ? 'border-b border-border' : '' }}">
                    <div class="flex justify-between">
                        <p class="text-sm font-medium">{{ $report->title }}</p>
                        <x-status-badge :status="$report->status" />
                    </div>
                    <p class="text-xs text-secondary">{{ $report->created_at->diffForHumans() }}</p>
                </a>
            @empty
                <p class="text-sm text-secondary">No reports yet.</p>
            @endforelse
        </x-card>

        {{-- Upcoming meetings --}}
        <x-card title="Upcoming Meetings">
            @forelse($upcomingMeetings as $meeting)
                <a href="{{ route('meetings.show', [$student, $meeting]) }}" class="block py-2.5 {{ !$loop->last ? 'border-b border-border' : '' }}">
                    <p class="text-sm font-medium">{{ $meeting->title }}</p>
                    <p class="text-xs text-secondary">{{ $meeting->scheduled_at->format('d M Y, h:i A') }}</p>
                </a>
            @empty
                <p class="text-sm text-secondary">No upcoming meetings.</p>
            @endforelse
        </x-card>
    </div>
</x-layouts.app>
