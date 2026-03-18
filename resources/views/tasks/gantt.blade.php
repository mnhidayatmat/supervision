<x-layouts.app title="Timeline">
    <x-slot:header>
        <div class="flex items-center gap-2">
            <span>Timeline</span>
            <span class="text-xs text-secondary">{{ $student->user->name }}</span>
        </div>
    </x-slot:header>

    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-2">
            <a href="{{ route('tasks.index', $student) }}" class="px-3 py-1.5 text-xs font-medium rounded-md text-secondary hover:bg-gray-100">List</a>
            <a href="{{ route('tasks.kanban', $student) }}" class="px-3 py-1.5 text-xs font-medium rounded-md text-secondary hover:bg-gray-100">Kanban</a>
            <a href="{{ route('tasks.gantt', $student) }}" class="px-3 py-1.5 text-xs font-medium rounded-md bg-primary text-white">Timeline</a>
        </div>
        <x-button href="{{ route('tasks.create', $student) }}" variant="accent" size="sm">+ New Task</x-button>
    </div>

    @if($tasks->isEmpty())
        <x-card>
            <div class="text-center py-8">
                <p class="text-sm text-secondary">No tasks with dates yet. Create tasks with start and due dates to see the timeline.</p>
            </div>
        </x-card>
    @else
        <x-card :padding="false">
            <div id="gantt-container" class="overflow-x-auto"></div>
        </x-card>
    @endif

    @push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/frappe-gantt@0.6.1/dist/frappe-gantt.min.css">
    <style>
        .gantt .bar { fill: #D97706; }
        .gantt .bar-progress { fill: #B45309; }
        .gantt .bar-label { fill: #fff; font-size: 11px; }
        .gantt .grid-header { fill: #F7F7F5; stroke: #E5E7EB; }
        .gantt .grid-row { fill: #fff; }
        .gantt .grid-row:nth-child(even) { fill: #FAFAFA; }
        .gantt .tick { stroke: #E5E7EB; }
        .gantt .today-highlight { fill: rgba(217, 119, 6, 0.08); }
        .gantt-completed .bar { fill: #34D399; }
        .gantt-completed .bar-progress { fill: #059669; }
        .gantt-in_progress .bar { fill: #FBBF24; }
        .gantt-waiting_review .bar { fill: #F97316; }
    </style>
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/frappe-gantt@0.6.1/dist/frappe-gantt.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tasks = @json($tasks->map(fn($t) => [
                'id' => (string)$t->id,
                'name' => $t->title,
                'start' => $t->start_date->format('Y-m-d'),
                'end' => $t->due_date->format('Y-m-d'),
                'progress' => $t->progress,
                'custom_class' => 'gantt-' . $t->status,
            ]));

            if (tasks.length === 0) return;

            const gantt = new Gantt('#gantt-container', tasks, {
                view_mode: 'Week',
                date_format: 'YYYY-MM-DD',
                bar_height: 24,
                bar_corner_radius: 4,
                padding: 16,
                on_date_change: function(task, start, end) {
                    axios.put(`/api/tasks/${task.id}/dates`, {
                        start_date: start.toISOString().split('T')[0],
                        due_date: end.toISOString().split('T')[0]
                    }).catch(console.error);
                },
                on_click: function(task) {
                    window.location.href = `/students/{{ $student->id }}/tasks/${task.id}`;
                },
                custom_popup_html: function(task) {
                    return `<div class="bg-white border border-gray-200 rounded-lg shadow-lg p-3 text-xs">
                        <p class="font-semibold text-sm mb-1">${task.name}</p>
                        <p class="text-gray-500">Progress: ${task.progress}%</p>
                    </div>`;
                }
            });
        });
    </script>
    @endpush
</x-layouts.app>
