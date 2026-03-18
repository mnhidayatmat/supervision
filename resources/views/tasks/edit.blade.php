<x-layouts.app title="Edit Task">
    <x-slot:header>Edit Task</x-slot:header>

    <div class="max-w-2xl">
        <x-card>
            <form method="POST" action="{{ route('tasks.update', [$student, $task]) }}" class="space-y-4">
                @csrf @method('PUT')
                <x-input name="title" label="Title" required :value="$task->title" />
                <x-textarea name="description" label="Description">{{ $task->description }}</x-textarea>

                <div class="grid sm:grid-cols-2 gap-4">
                    <x-select name="milestone_id" label="Milestone" :options="$milestones->pluck('name', 'id')->toArray()" :selected="$task->milestone_id" />
                    <x-select name="status" label="Status" required :options="array_combine(\App\Models\Task::STATUSES, array_map(fn($s) => ucfirst(str_replace('_', ' ', $s)), \App\Models\Task::STATUSES))" :selected="$task->status" />
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <x-select name="priority" label="Priority" required :options="['low' => 'Low', 'medium' => 'Medium', 'high' => 'High', 'urgent' => 'Urgent']" :selected="$task->priority" />
                    <x-input name="progress" type="number" label="Progress (%)" :value="$task->progress" min="0" max="100" />
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <x-input name="start_date" type="date" label="Start Date" :value="$task->start_date?->format('Y-m-d')" />
                    <x-input name="due_date" type="date" label="Due Date" :value="$task->due_date?->format('Y-m-d')" />
                </div>

                <x-input name="estimated_hours" type="number" label="Estimated Hours" :value="$task->estimated_hours" />

                <div class="flex items-center gap-3 pt-2">
                    <x-button type="submit" variant="accent">Save Changes</x-button>
                    <x-button href="{{ route('tasks.show', [$student, $task]) }}" variant="secondary">Cancel</x-button>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>
