<x-layouts.app title="Kanban Board">
    <x-slot:header>
        <div class="flex items-center gap-2">
            <span>Kanban Board</span>
            <span class="text-xs text-secondary">{{ $student->user->name }}</span>
        </div>
    </x-slot:header>

    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-2">
            <a href="{{ route('tasks.index', $student) }}" class="px-3 py-1.5 text-xs font-medium rounded-md text-secondary hover:bg-gray-100">List</a>
            <a href="{{ route('tasks.kanban', $student) }}" class="px-3 py-1.5 text-xs font-medium rounded-md bg-primary text-white">Kanban</a>
            <a href="{{ route('tasks.gantt', $student) }}" class="px-3 py-1.5 text-xs font-medium rounded-md text-secondary hover:bg-gray-100">Timeline</a>
        </div>
        <x-button href="{{ route('tasks.create', $student) }}" variant="accent" size="sm">+ New Task</x-button>
    </div>

    <div x-data="kanbanBoard()" x-init="init()" class="flex gap-4 overflow-x-auto pb-4">
        @php
            $columns = [
                'backlog' => 'Backlog',
                'planned' => 'Planned',
                'in_progress' => 'In Progress',
                'waiting_review' => 'Waiting Review',
                'revision' => 'Revision',
                'completed' => 'Completed',
            ];
            $columnColors = [
                'backlog' => 'bg-gray-400',
                'planned' => 'bg-blue-400',
                'in_progress' => 'bg-yellow-400',
                'waiting_review' => 'bg-orange-400',
                'revision' => 'bg-purple-400',
                'completed' => 'bg-green-400',
            ];
        @endphp

        @foreach($columns as $status => $label)
            <div class="flex-shrink-0 w-64">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-2 h-2 rounded-full {{ $columnColors[$status] }}"></div>
                    <h3 class="text-xs font-semibold text-secondary uppercase tracking-wider">{{ $label }}</h3>
                    <span class="text-[10px] text-secondary/60 bg-gray-100 px-1.5 rounded">{{ ($tasks[$status] ?? collect())->count() }}</span>
                </div>
                <div
                    id="col-{{ $status }}"
                    data-status="{{ $status }}"
                    class="space-y-2 min-h-[200px] p-1 rounded-lg bg-gray-50/50"
                >
                    @foreach(($tasks[$status] ?? []) as $task)
                        <div
                            class="kanban-card bg-white border border-border rounded-lg p-3 cursor-move hover:shadow-sm transition-shadow"
                            data-id="{{ $task->id }}"
                        >
                            <a href="{{ route('tasks.show', [$student, $task]) }}" class="text-sm font-medium text-primary hover:text-accent block mb-1">{{ $task->title }}</a>
                            <div class="flex items-center gap-2">
                                <x-badge :color="match($task->priority) { 'urgent' => 'red', 'high' => 'orange', 'medium' => 'yellow', default => 'gray' }">{{ ucfirst($task->priority) }}</x-badge>
                                @if($task->due_date)
                                    <span class="text-[10px] text-secondary">{{ $task->due_date->format('d M') }}</span>
                                @endif
                            </div>
                            @if($task->progress > 0)
                                <div class="mt-2 w-full bg-gray-100 rounded-full h-1">
                                    <div class="bg-accent h-1 rounded-full" style="width: {{ $task->progress }}%"></div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        function kanbanBoard() {
            return {
                init() {
                    document.querySelectorAll('[id^="col-"]').forEach(col => {
                        new Sortable(col, {
                            group: 'kanban',
                            animation: 150,
                            ghostClass: 'opacity-30',
                            dragClass: 'shadow-lg',
                            onEnd: (evt) => {
                                const taskId = evt.item.dataset.id;
                                const newStatus = evt.to.dataset.status;
                                const tasks = [...evt.to.children].map((el, i) => ({
                                    id: parseInt(el.dataset.id),
                                    sort_order: i,
                                    status: newStatus
                                }));
                                axios.post('/api/tasks/reorder', { tasks }).catch(console.error);
                                axios.put(`/api/tasks/${taskId}/status`, { status: newStatus }).catch(console.error);
                            }
                        });
                    });
                }
            };
        }
    </script>
    @endpush
</x-layouts.app>
