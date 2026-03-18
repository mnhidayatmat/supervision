<x-layouts.app title="{{ $task->title }}">
    <x-slot:header>Task Detail</x-slot:header>

    <div class="max-w-3xl">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-lg font-semibold">{{ $task->title }}</h2>
                <p class="text-xs text-secondary mt-0.5">Created {{ $task->created_at->diffForHumans() }} @if($task->assignedBy) by {{ $task->assignedBy->name }} @endif</p>
            </div>
            <div class="flex items-center gap-2">
                <x-button href="{{ route('tasks.edit', [$student, $task]) }}" variant="secondary" size="sm">Edit</x-button>
                <form method="POST" action="{{ route('tasks.destroy', [$student, $task]) }}" onsubmit="return confirm('Delete this task?')">
                    @csrf @method('DELETE')
                    <x-button type="submit" variant="ghost" size="sm" class="text-red-500 hover:text-red-700">Delete</x-button>
                </form>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-4">
                <x-card title="Description">
                    <div class="text-sm text-secondary leading-relaxed">
                        {!! nl2br(e($task->description ?? 'No description.')) !!}
                    </div>
                </x-card>

                {{-- Subtasks --}}
                @if($task->subtasks->count())
                    <x-card title="Subtasks">
                        @foreach($task->subtasks as $sub)
                            <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b border-border' : '' }}">
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full {{ $sub->status === 'completed' ? 'bg-green-500' : 'bg-gray-300' }}"></div>
                                    <span class="text-sm {{ $sub->status === 'completed' ? 'line-through text-secondary' : '' }}">{{ $sub->title }}</span>
                                </div>
                                <x-status-badge :status="$sub->status" />
                            </div>
                        @endforeach
                    </x-card>
                @endif

                {{-- Revisions --}}
                @if($task->revisions->count())
                    <x-card title="Revisions">
                        @foreach($task->revisions as $rev)
                            <div class="py-3 {{ !$loop->last ? 'border-b border-border' : '' }}">
                                <div class="flex justify-between mb-1">
                                    <p class="text-sm font-medium">{{ $rev->requestedBy->name }}</p>
                                    <x-status-badge :status="$rev->status" />
                                </div>
                                <p class="text-sm text-secondary">{{ $rev->description }}</p>
                                @foreach($rev->comments as $comment)
                                    <div class="ml-4 mt-2 pl-3 border-l-2 border-border">
                                        <p class="text-xs text-secondary"><strong>{{ $comment->user->name }}</strong> &middot; {{ $comment->created_at->diffForHumans() }}</p>
                                        <p class="text-sm">{{ $comment->content }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </x-card>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="space-y-4">
                <x-card>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-secondary uppercase tracking-wide">Status</p>
                            <x-status-badge :status="$task->status" />
                        </div>
                        <div>
                            <p class="text-xs text-secondary uppercase tracking-wide">Priority</p>
                            <x-badge :color="match($task->priority) { 'urgent' => 'red', 'high' => 'orange', 'medium' => 'yellow', default => 'gray' }">{{ ucfirst($task->priority) }}</x-badge>
                        </div>
                        <div>
                            <p class="text-xs text-secondary uppercase tracking-wide">Progress</p>
                            <div class="flex items-center gap-2 mt-1">
                                <div class="flex-1 bg-gray-100 rounded-full h-2">
                                    <div class="bg-accent h-2 rounded-full" style="width: {{ $task->progress }}%"></div>
                                </div>
                                <span class="text-xs font-medium">{{ $task->progress }}%</span>
                            </div>
                        </div>
                        @if($task->milestone)
                            <div>
                                <p class="text-xs text-secondary uppercase tracking-wide">Milestone</p>
                                <p class="text-sm">{{ $task->milestone->name }}</p>
                            </div>
                        @endif
                        @if($task->start_date)
                            <div>
                                <p class="text-xs text-secondary uppercase tracking-wide">Start</p>
                                <p class="text-sm">{{ $task->start_date->format('d M Y') }}</p>
                            </div>
                        @endif
                        @if($task->due_date)
                            <div>
                                <p class="text-xs text-secondary uppercase tracking-wide">Due</p>
                                <p class="text-sm">{{ $task->due_date->format('d M Y') }}</p>
                            </div>
                        @endif
                        @if($task->dependencies->count())
                            <div>
                                <p class="text-xs text-secondary uppercase tracking-wide">Dependencies</p>
                                @foreach($task->dependencies as $dep)
                                    <a href="{{ route('tasks.show', [$student, $dep]) }}" class="text-xs text-accent hover:underline block">{{ $dep->title }}</a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </x-card>
            </div>
        </div>
    </div>
</x-layouts.app>
