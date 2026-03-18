<x-layouts.app title="Supervisor Dashboard">
    <x-slot:header>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-lg font-semibold text-gray-900">Supervisor Dashboard</h1>
                <p class="text-sm text-gray-500">Welcome back, {{ auth()->user()->name }}</p>
            </div>
            @if(auth()->user()->staff_id)
                <div class="text-right">
                    <p class="text-xs text-gray-500">Staff ID</p>
                    <p class="text-sm font-medium">{{ auth()->user()->staff_id }}</p>
                </div>
            @endif
        </div>
    </x-slot:header>

    {{-- Statistics Overview --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_students'] }}</p>
                    <p class="text-xs text-gray-500">Total Students</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['active_students'] }}</p>
                    <p class="text-xs text-gray-500">Active</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-yellow-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending_reviews'] }}</p>
                    <p class="text-xs text-gray-500">Pending Reviews</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['tasks_waiting_review'] }}</p>
                    <p class="text-xs text-gray-500">Tasks to Review</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-indigo-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['upcoming_meetings'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Upcoming Meetings</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        {{-- Students Overview --}}
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200">
            <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-gray-900">My Students</h2>
                <a href="{{ route('supervisor.students.index') }}" class="text-xs text-accent hover:underline">View All</a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($students as $s)
                    <a href="{{ route('supervisor.students.show', $s) }}" class="flex items-center justify-between p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                <span class="text-sm font-medium text-gray-600">{{ substr($s->user->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $s->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $s->programme->name }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="text-right">
                                @if($s->research_title)
                                    <p class="text-xs text-gray-600 max-w-[150px] truncate">{{ $s->research_title }}</p>
                                @else
                                    <p class="text-xs text-gray-400">No title yet</p>
                                @endif
                                <p class="text-xs text-gray-400">{{ $s->matric_number ?? 'N/A' }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-20 bg-gray-100 rounded-full h-2">
                                    <div class="bg-accent h-2 rounded-full transition-all" style="width: {{ $s->overall_progress ?? 0 }}%"></div>
                                </div>
                                <span class="text-xs font-medium text-gray-600 w-8">{{ $s->overall_progress ?? 0 }}%</span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="p-8 text-center">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <p class="text-sm text-gray-500">No students assigned yet</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Pending Actions --}}
        <div class="space-y-6">
            {{-- Reports Awaiting Review --}}
            <div class="bg-white rounded-xl border border-gray-200">
                <div class="p-4 border-b border-gray-100">
                    <h2 class="text-sm font-semibold text-gray-900">Reports Awaiting Review</h2>
                </div>
                <div class="divide-y divide-gray-100 max-h-[300px] overflow-y-auto">
                    @forelse($pendingReports as $report)
                        <a href="{{ route('reports.show', [$report->student_id, $report]) }}" class="block p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 bg-yellow-50 rounded-lg flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-4 h-4 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $report->title }}</p>
                                    <p class="text-xs text-gray-500">{{ $report->student->user->name }}</p>
                                    <p class="text-xs text-gray-400 mt-1">{{ $report->submitted_at?->diffForHumans() ?? 'Recently' }}</p>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="p-6 text-center">
                            <p class="text-sm text-gray-500">No reports pending review</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-gradient-to-br from-accent to-accent/80 rounded-xl p-4 text-white">
                <h3 class="text-sm font-semibold mb-3">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('supervisor.students.index') }}" class="flex items-center gap-2 p-2 bg-white/10 rounded-lg hover:bg-white/20 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <span class="text-sm">View All Students</span>
                    </a>
                    <a href="{{ route('ai.chat') }}" class="flex items-center gap-2 p-2 bg-white/10 rounded-lg hover:bg-white/20 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                        <span class="text-sm">AI Assistant</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Tasks Waiting Review (Full Width) --}}
        <div class="lg:col-span-3 bg-white rounded-xl border border-gray-200">
            <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-gray-900">Tasks Waiting Review</h2>
                @if($stats['tasks_waiting_review'] > 0)
                    <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs font-medium rounded-full">{{ $stats['tasks_waiting_review'] }} pending</span>
                @endif
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($tasksForReview as $task)
                    <a href="{{ route('tasks.show', [$task->student_id, $task]) }}" class="flex items-center justify-between p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $task->title }}</p>
                                <p class="text-xs text-gray-500">{{ $task->student->user->name }} &middot; Due: {{ $task->due_date?->format('M d, Y') ?? 'No due date' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs text-gray-400">{{ $task->created_at->diffForHumans() }}</span>
                            <x-status-badge :status="$task->status" />
                        </div>
                    </a>
                @empty
                    <div class="p-6 text-center">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-sm text-gray-500">All tasks are up to date</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.app>
