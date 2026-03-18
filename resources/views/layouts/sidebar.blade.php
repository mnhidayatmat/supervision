@php $role = auth()->user()->role; @endphp

<div class="flex flex-col h-full">
    {{-- Logo --}}
    <div class="flex items-center gap-2 h-14 px-4 border-b border-border shrink-0">
        <div class="w-7 h-7 bg-accent rounded-lg flex items-center justify-center">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
        </div>
        <span class="font-semibold text-sm text-primary">ResearchFlow</span>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto py-3 px-2 space-y-0.5">
        @if($role === 'admin')
            <x-nav-item href="{{ route('admin.dashboard') }}" icon="home" :active="request()->routeIs('admin.dashboard')">Dashboard</x-nav-item>
            <x-nav-item href="{{ route('admin.students.index') }}" icon="users" :active="request()->routeIs('admin.students.*')">Students</x-nav-item>
            <x-nav-item href="{{ route('admin.programmes.index') }}" icon="folder" :active="request()->routeIs('admin.programmes.*')">Programmes</x-nav-item>
            <x-nav-item href="{{ route('admin.templates.index') }}" icon="map" :active="request()->routeIs('admin.templates.*')">Journey Templates</x-nav-item>

            <div class="pt-3 pb-1 px-2">
                <p class="text-[10px] font-semibold uppercase tracking-wider text-secondary/60">Settings</p>
            </div>
            <x-nav-item href="{{ route('admin.settings.storage') }}" icon="server" :active="request()->routeIs('admin.settings.storage')">Storage</x-nav-item>
            <x-nav-item href="{{ route('admin.settings.ai') }}" icon="cpu" :active="request()->routeIs('admin.settings.ai')">AI Providers</x-nav-item>
            <x-nav-item href="{{ route('admin.settings.users') }}" icon="shield" :active="request()->routeIs('admin.settings.users')">Users</x-nav-item>

        @elseif(in_array($role, ['supervisor', 'cosupervisor']))
            <x-nav-item href="{{ route('supervisor.dashboard') }}" icon="home" :active="request()->routeIs('supervisor.dashboard')">Dashboard</x-nav-item>
            <x-nav-item href="{{ route('supervisor.students.index') }}" icon="users" :active="request()->routeIs('supervisor.students.*')">My Students</x-nav-item>

            @php $activeStudentId = \App\Models\Student::where('supervisor_id', auth()->id())->orWhere('cosupervisor_id', auth()->id())->where('status', 'active')->first()?->id; @endphp
            @if($activeStudentId)
                <div class="pt-3 pb-1 px-2">
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-secondary/60">Supervision</p>
                </div>
                <x-nav-item href="{{ route('tasks.index', $activeStudentId) }}" icon="check-square" :active="request()->routeIs('tasks.*')">Tasks</x-nav-item>
                <x-nav-item href="{{ route('tasks.kanban', $activeStudentId) }}" icon="columns" :active="request()->routeIs('tasks.kanban')">Kanban Board</x-nav-item>
                <x-nav-item href="{{ route('reports.index', $activeStudentId) }}" icon="file-text" :active="request()->routeIs('reports.*')">Progress Reports</x-nav-item>
                <x-nav-item href="{{ route('meetings.index', $activeStudentId) }}" icon="calendar" :active="request()->routeIs('meetings.*')">Meetings</x-nav-item>
            @endif

        @elseif($role === 'student')
            @php $studentId = auth()->user()->student?->id; @endphp
            <x-nav-item href="{{ route('student.dashboard') }}" icon="home" :active="request()->routeIs('student.dashboard')">Dashboard</x-nav-item>
            @if($studentId)
                <x-nav-item href="{{ route('tasks.index', $studentId) }}" icon="check-square" :active="request()->routeIs('tasks.*')">Tasks</x-nav-item>
                <x-nav-item href="{{ route('tasks.kanban', $studentId) }}" icon="columns" :active="request()->routeIs('tasks.kanban')">Kanban</x-nav-item>
                <x-nav-item href="{{ route('tasks.gantt', $studentId) }}" icon="bar-chart" :active="request()->routeIs('tasks.gantt')">Timeline</x-nav-item>
                <x-nav-item href="{{ route('reports.index', $studentId) }}" icon="file-text" :active="request()->routeIs('reports.*')">Reports</x-nav-item>
                <x-nav-item href="{{ route('meetings.index', $studentId) }}" icon="calendar" :active="request()->routeIs('meetings.*')">Meetings</x-nav-item>
                <x-nav-item href="{{ route('files.index', $studentId) }}" icon="archive" :active="request()->routeIs('files.*')">Research Vault</x-nav-item>
            @endif
        @endif

        <div class="pt-3 pb-1 px-2">
            <p class="text-[10px] font-semibold uppercase tracking-wider text-secondary/60">AI</p>
        </div>
        <x-nav-item href="{{ route('ai.chat') }}" icon="message-circle" :active="request()->routeIs('ai.*')">AI Assistant</x-nav-item>
    </nav>
</div>
