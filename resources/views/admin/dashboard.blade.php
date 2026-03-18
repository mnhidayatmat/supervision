<x-layouts.app title="Admin Dashboard">
    <x-slot:header>Dashboard</x-slot:header>

    {{-- Welcome & Quick Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-lg font-semibold text-primary">Welcome back, {{ auth()->user()->name }}</h2>
            <p class="text-sm text-secondary mt-0.5">Here's what's happening with your supervision program today.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.students.create') }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-white bg-accent hover:bg-amber-600 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Student
            </a>
            <a href="{{ route('admin.programmes.create') }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-primary bg-white border border-border hover:bg-gray-50 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Programme
            </a>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="group relative bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl p-4 text-white overflow-hidden transition-transform hover:scale-[1.02]">
            <div class="absolute right-0 top-0 w-20 h-20 bg-white/10 rounded-full -mr-6 -mt-6"></div>
            <div class="relative">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span class="text-xs font-medium text-white/80 uppercase tracking-wide">Total Students</span>
                </div>
                <p class="text-3xl font-bold">{{ $stats['total_students'] }}</p>
                <p class="text-xs text-white/70 mt-1">{{ $stats['active_students'] }} active</p>
            </div>
        </div>

        <div class="group relative bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-4 text-white overflow-hidden transition-transform hover:scale-[1.02]">
            <div class="absolute right-0 top-0 w-20 h-20 bg-white/10 rounded-full -mr-6 -mt-6"></div>
            <div class="relative">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-xs font-medium text-white/80 uppercase tracking-wide">Pending Approval</span>
                </div>
                <p class="text-3xl font-bold">{{ $stats['pending_students'] }}</p>
                <p class="text-xs text-white/70 mt-1">{{ $stats['pending_students'] > 0 ? 'Awaiting action' : 'All caught up' }}</p>
            </div>
        </div>

        <div class="group relative bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl p-4 text-white overflow-hidden transition-transform hover:scale-[1.02]">
            <div class="absolute right-0 top-0 w-20 h-20 bg-white/10 rounded-full -mr-6 -mt-6"></div>
            <div class="relative">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span class="text-xs font-medium text-white/80 uppercase tracking-wide">Supervisors</span>
                </div>
                <p class="text-3xl font-bold">{{ $stats['total_supervisors'] }}</p>
                <p class="text-xs text-white/70 mt-1">Active supervisors</p>
            </div>
        </div>

        <div class="group relative bg-gradient-to-br from-violet-500 to-violet-600 rounded-xl p-4 text-white overflow-hidden transition-transform hover:scale-[1.02]">
            <div class="absolute right-0 top-0 w-20 h-20 bg-white/10 rounded-full -mr-6 -mt-6"></div>
            <div class="relative">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    <span class="text-xs font-medium text-white/80 uppercase tracking-wide">Total Tasks</span>
                </div>
                <p class="text-3xl font-bold">{{ $stats['total_tasks'] }}</p>
                <p class="text-xs text-white/70 mt-1">{{ $stats['pending_reports'] }} reports pending</p>
            </div>
        </div>
    </div>

    {{-- Secondary Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg border border-border p-4 flex items-center gap-3 hover:shadow-md transition-shadow">
            <div class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <p class="text-xs text-secondary">Upcoming Meetings</p>
                <p class="text-lg font-semibold text-primary">{{ $stats['upcoming_meetings'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-border p-4 flex items-center gap-3 hover:shadow-md transition-shadow">
            <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div>
                <p class="text-xs text-secondary">Pending Reports</p>
                <p class="text-lg font-semibold text-primary">{{ $stats['pending_reports'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-border p-4 flex items-center gap-3 hover:shadow-md transition-shadow cursor-pointer" onclick="window.location='{{ route('admin.programmes.index') }}'">
            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
            <div>
                <p class="text-xs text-secondary">Programmes</p>
                <p class="text-lg font-semibold text-primary">Manage</p>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-border p-4 flex items-center gap-3 hover:shadow-md transition-shadow cursor-pointer" onclick="window.location='{{ route('admin.settings.users') }}'">
            <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
                <p class="text-xs text-secondary">Settings</p>
                <p class="text-lg font-semibold text-primary">Configure</p>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        {{-- Pending approvals --}}
        <x-card title="Pending Approvals" class="lg:col-span-1">
            @forelse($pendingApprovals as $s)
                <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-border' : '' }} group">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-semibold">
                            {{ substr($s->user->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-primary group-hover:text-accent transition-colors">{{ $s->user->name }}</p>
                            <p class="text-xs text-secondary">{{ $s->programme->name }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.students.show', $s) }}" class="p-1.5 text-secondary hover:text-primary hover:bg-gray-100 rounded-lg transition-colors" title="View details">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                        <form method="POST" action="{{ route('admin.students.approve', $s) }}" class="inline">
                            @csrf
                            <button type="submit" class="p-1.5 text-green-600 hover:text-green-700 hover:bg-green-50 rounded-lg transition-colors" title="Approve">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-6">
                    <div class="w-12 h-12 rounded-full bg-green-100 text-green-600 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <p class="text-sm text-secondary">No pending approvals</p>
                </div>
            @endforelse
        </x-card>

        {{-- Task distribution chart --}}
        <x-card title="Tasks by Status" class="lg:col-span-1" :padding="false">
            <div class="p-5">
                <canvas id="taskChart" height="180"></canvas>
            </div>
            <div class="px-5 pb-4 grid grid-cols-3 gap-2 text-center">
                <div class="bg-gray-50 rounded-lg p-2">
                    <p class="text-lg font-bold text-primary">{{ $tasksByStatus['in_progress'] ?? 0 }}</p>
                    <p class="text-xs text-secondary">Active</p>
                </div>
                <div class="bg-amber-50 rounded-lg p-2">
                    <p class="text-lg font-bold text-amber-600">{{ $tasksByStatus['waiting_review'] ?? 0 }}</p>
                    <p class="text-xs text-secondary">Review</p>
                </div>
                <div class="bg-green-50 rounded-lg p-2">
                    <p class="text-lg font-bold text-green-600">{{ $tasksByStatus['completed'] ?? 0 }}</p>
                    <p class="text-xs text-secondary">Done</p>
                </div>
            </div>
        </x-card>

        {{-- Quick overview --}}
        <x-card title="Quick Overview" class="lg:col-span-1">
            <div class="space-y-4">
                <a href="{{ route('admin.students.index') }}" class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-primary">All Students</p>
                            <p class="text-xs text-secondary">View and manage students</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-secondary group-hover:text-primary group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>

                <a href="{{ route('admin.templates.index') }}" class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-violet-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-primary">Templates</p>
                            <p class="text-xs text-secondary">Task & report templates</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-secondary group-hover:text-primary group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>

                <a href="{{ route('admin.settings.users') }}" class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-primary">Manage Users</p>
                            <p class="text-xs text-secondary">Supervisors & staff</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-secondary group-hover:text-primary group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </x-card>
    </div>

    {{-- Recent students --}}
    <x-card title="Recent Students" class="mt-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs font-medium text-secondary uppercase tracking-wider border-b border-border">
                        <th class="pb-3 font-semibold">Student</th>
                        <th class="pb-3 font-semibold">Programme</th>
                        <th class="pb-3 font-semibold">Supervisor</th>
                        <th class="pb-3 font-semibold">Status</th>
                        <th class="pb-3 font-semibold">Progress</th>
                        <th class="pb-3 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach($recentStudents as $s)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-accent/10 text-accent flex items-center justify-center text-xs font-semibold">
                                        {{ substr($s->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.students.show', $s) }}" class="font-medium text-primary hover:text-accent transition-colors">{{ $s->user->name }}</a>
                                        <p class="text-xs text-secondary">{{ $s->user->matric_number }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3">
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-700">{{ $s->programme->code }}</span>
                            </td>
                            <td class="py-3 text-secondary">{{ $s->supervisor?->name ?? '—' }}</td>
                            <td class="py-3"><x-status-badge :status="$s->status" /></td>
                            <td class="py-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-20 bg-gray-100 rounded-full h-2 overflow-hidden">
                                        <div class="bg-gradient-to-r from-amber-500 to-amber-600 h-2 rounded-full transition-all duration-500" style="width: {{ $s->overall_progress }}%"></div>
                                    </div>
                                    <span class="text-xs font-medium {{ $s->overall_progress >= 75 ? 'text-green-600' : ($s->overall_progress >= 40 ? 'text-amber-600' : 'text-gray-600') }}">{{ $s->overall_progress }}%</span>
                                </div>
                            </td>
                            <td class="py-3 text-right">
                                <a href="{{ route('admin.students.show', $s) }}" class="inline-flex items-center gap-1 text-xs font-medium text-accent hover:text-amber-700 transition-colors">
                                    View
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($recentStudents->count() > 0)
        <div class="mt-4 pt-3 border-t border-border text-center">
            <a href="{{ route('admin.students.index') }}" class="inline-flex items-center gap-1 text-sm font-medium text-accent hover:text-amber-700 transition-colors">
                View all students
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
        @endif
    </x-card>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const taskData = @json($tasksByStatus);
        const labels = ['Backlog', 'Planned', 'Active', 'Review', 'Revision', 'Done'];
        const keys = ['backlog', 'planned', 'in_progress', 'waiting_review', 'revision', 'completed'];
        const colors = ['#E5E7EB', '#93C5FD', '#3B82F6', '#F59E0B', '#A78BFA', '#10B981'];

        new Chart(document.getElementById('taskChart'), {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: keys.map(k => taskData[k] || 0),
                    backgroundColor: colors,
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            font: { size: 11 },
                            padding: 12,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    }
                },
                cutout: '70%',
            }
        });
    </script>
    @endpush
</x-layouts.app>
