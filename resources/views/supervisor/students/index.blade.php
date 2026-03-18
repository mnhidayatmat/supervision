<x-layouts.app title="My Students">
    <x-slot:header>My Students</x-slot:header>

    {{-- Page header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-base font-semibold text-primary">My Students</h2>
            <p class="text-xs text-secondary mt-0.5">Students under your supervision</p>
        </div>
    </div>

    {{-- Search & filter --}}
    <x-card class="mb-4">
        <form method="GET" action="{{ route('supervisor.students.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-medium text-secondary mb-1">Search</label>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search by name or research title..."
                    class="w-full rounded-lg border border-border bg-white px-3 py-2 text-sm text-primary placeholder-secondary/50 focus:border-accent focus:ring-1 focus:ring-accent/30 outline-none transition-colors"
                >
            </div>
            <div class="min-w-[140px]">
                <label class="block text-xs font-medium text-secondary mb-1">Programme</label>
                <select name="programme_id" class="w-full rounded-lg border border-border bg-white px-3 py-2 text-sm text-primary focus:border-accent focus:ring-1 focus:ring-accent/30 outline-none transition-colors">
                    <option value="">All Programmes</option>
                    @foreach($students->pluck('programme')->unique('id')->filter() as $programme)
                        <option value="{{ $programme->id }}" {{ request('programme_id') == $programme->id ? 'selected' : '' }}>
                            {{ $programme->code }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[130px]">
                <label class="block text-xs font-medium text-secondary mb-1">Status</label>
                <select name="status" class="w-full rounded-lg border border-border bg-white px-3 py-2 text-sm text-primary focus:border-accent focus:ring-1 focus:ring-accent/30 outline-none transition-colors">
                    <option value="">All</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="on_hold" {{ request('status') === 'on_hold' ? 'selected' : '' }}>On Hold</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <div class="flex gap-2">
                <x-button type="submit" variant="primary" size="sm">Filter</x-button>
                @if(request()->hasAny(['search', 'programme_id', 'status']))
                    <x-button href="{{ route('supervisor.students.index') }}" variant="secondary" size="sm">Clear</x-button>
                @endif
            </div>
        </form>
    </x-card>

    {{-- Students grid --}}
    @if($students->isEmpty())
        <x-card>
            <div class="py-12 text-center text-secondary">
                <svg class="w-10 h-10 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <p class="text-sm font-medium">No students assigned</p>
                <p class="text-xs mt-1">Students will appear here once assigned to you</p>
            </div>
        </x-card>
    @else
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($students as $student)
                <a href="{{ route('supervisor.students.show', $student) }}" class="group block">
                    <x-card class="h-full hover:shadow-sm hover:border-gray-300 transition-all">
                        {{-- Student header --}}
                        <div class="flex items-start gap-3 mb-4">
                            <div class="w-10 h-10 rounded-full bg-accent/10 text-accent flex items-center justify-center text-sm font-semibold flex-shrink-0">
                                {{ substr($student->user->name, 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-primary group-hover:text-accent transition-colors truncate">{{ $student->user->name }}</p>
                                <p class="text-xs text-secondary font-mono">{{ $student->user->matric_number ?? 'No matric' }}</p>
                            </div>
                            <x-status-badge :status="$student->status" />
                        </div>

                        {{-- Research info --}}
                        <div class="space-y-2 mb-4">
                            <div>
                                <p class="text-[10px] font-medium text-secondary uppercase tracking-wide">Programme</p>
                                <p class="text-xs text-primary mt-0.5">{{ $student->programme->name ?? '—' }}</p>
                            </div>
                            @if($student->research_title)
                                <div>
                                    <p class="text-[10px] font-medium text-secondary uppercase tracking-wide">Research Title</p>
                                    <p class="text-xs text-primary mt-0.5 line-clamp-2">{{ $student->research_title }}</p>
                                </div>
                            @endif
                        </div>

                        {{-- Progress bar --}}
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[10px] font-medium text-secondary uppercase tracking-wide">Progress</span>
                                <span class="text-xs text-primary font-medium">{{ $student->overall_progress ?? 0 }}%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-1.5">
                                <div class="bg-accent h-1.5 rounded-full transition-all" style="width: {{ $student->overall_progress ?? 0 }}%"></div>
                            </div>
                        </div>

                        {{-- Quick stats --}}
                        @if($student->expected_completion)
                            <div class="mt-3 pt-3 border-t border-border flex items-center justify-between">
                                <span class="text-xs text-secondary">Expected completion</span>
                                <span class="text-xs text-primary font-medium">{{ $student->expected_completion->format('M Y') }}</span>
                            </div>
                        @endif
                    </x-card>
                </a>
            @endforeach
        </div>

        @if($students->hasPages())
            <div class="mt-4">
                {{ $students->withQueryString()->links() }}
            </div>
        @endif
    @endif
</x-layouts.app>
