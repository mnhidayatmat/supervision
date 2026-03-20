<x-layouts.app title="Research Journey - {{ $student->user->name }}" :header="'Research Journey'">
    <div class="max-w-[1600px] mx-auto" x-data="studentJourney({{ $student->id }}, {{ $journey->id }})" x-init="init()">
        {{-- Toast Notifications --}}
        <div class="fixed top-4 right-4 z-[100] space-y-2">
            <template x-for="notification in notifications" :key="notification.id">
                <div x-show="notification.show"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-x-4"
                     x-transition:enter-end="opacity-100 translate-x-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-x-0"
                     x-transition:leave-end="opacity-0 translate-x-4"
                     :class="{
                         'bg-success text-white': notification.type === 'success',
                         'bg-danger text-white': notification.type === 'error',
                         'bg-warning text-white': notification.type === 'warning',
                         'bg-info text-white': notification.type === 'info'
                     }"
                     class="px-5 py-3 rounded-xl shadow-lg flex items-center gap-3 min-w-[300px]">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="notification.type === 'success'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        <path x-show="notification.type === 'error'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        <path x-show="notification.type === 'warning'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        <path x-show="notification.type === 'info'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm font-medium" x-text="notification.message"></p>
                    </div>
                    <button @click="dismissNotification(notification.id)" class="opacity-70 hover:opacity-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </template>
        </div>

        {{-- Student Selector --}}
        <div class="mb-6">
            <div class="relative inline-block w-full sm:w-auto" x-data="{ open: false }">
                <button @click="open = !open"
                        class="flex items-center gap-3 px-4 py-2.5 rounded-xl border border-border
                               hover:border-accent/30 hover:bg-surface transition-all w-full sm:w-auto">
                    <x-avatar :name="$student->user->name" size="sm" />
                    <div class="text-left flex-1 sm:flex-none">
                        <p class="text-sm font-medium text-primary">{{ $student->user->name }}</p>
                        <p class="text-xs text-secondary">{{ $student->programme->name ?? 'Research' }}</p>
                    </div>
                    <svg class="w-4 h-4 text-tertiary" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div x-show="open" @click.outside="open = false" x-transition
                     class="absolute left-0 right-0 sm:right-auto sm:left-auto sm:w-64 mt-2 bg-card border border-border
                            rounded-xl shadow-lg z-50 max-h-80 overflow-y-auto">
                    <div class="p-2">
                        @foreach($students as $studentOption)
                            <a href="{{ route('journey.index', $studentOption) }}"
                               class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-surface transition-colors
                                      {{ $student->id === $studentOption->id ? 'bg-accent/10' : '' }}">
                                <x-avatar :name="$studentOption->user->name" size="sm" />
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-primary truncate">{{ $studentOption->user->name }}</p>
                                    <p class="text-xs text-secondary truncate">{{ $studentOption->programme->name ?? 'No Programme' }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-primary">Research Journey</h1>
                <p class="text-sm text-secondary mt-1">
                    Track progress through stages, milestones, and tasks
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('timeline.show', $student) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium
                          text-secondary hover:text-primary border border-border hover:bg-surface transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                    </svg>
                    View Timeline
                </a>
                <button @click="refreshData()"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium
                               text-secondary hover:text-primary border border-border hover:bg-surface transition-all">
                    <svg class="w-4 h-4" :class="{ 'animate-spin': loading }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Refresh
                </button>
            </div>
        </div>

        {{-- Journey Overview Stats --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-card rounded-2xl border border-border p-5 hover:border-accent/30 hover:shadow-soft transition-all">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-accent/20 to-accent/10 flex items-center justify-center">
                        <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-primary" x-text="stats.overall_progress + '%'"></p>
                        <p class="text-xs text-secondary">Overall Progress</p>
                    </div>
                </div>
                <div class="mt-3 h-2 bg-border-light rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-accent to-success rounded-full transition-all duration-500"
                         :style="'width: ' + stats.overall_progress + '%'"></div>
                </div>
            </div>

            <div class="bg-card rounded-2xl border border-border p-5 hover:border-accent/30 hover:shadow-soft transition-all">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-info/20 to-info/10 flex items-center justify-center">
                        <svg class="w-6 h-6 text-info" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-primary" x-text="stats.completed_stages + '/' + stats.total_stages"></p>
                        <p class="text-xs text-secondary">Stages Completed</p>
                    </div>
                </div>
            </div>

            <div class="bg-card rounded-2xl border border-border p-5 hover:border-accent/30 hover:shadow-soft transition-all">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-danger/20 to-danger/10 flex items-center justify-center">
                        <svg class="w-6 h-6 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-primary" x-text="stats.completed_milestones + '/' + stats.total_milestones"></p>
                        <p class="text-xs text-secondary">Milestones Done</p>
                    </div>
                </div>
            </div>

            <div class="bg-card rounded-2xl border border-border p-5 hover:border-accent/30 hover:shadow-soft transition-all">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-success/20 to-success/10 flex items-center justify-center">
                        <svg class="w-6 h-6 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-primary" x-text="stats.elapsed_weeks"></p>
                        <p class="text-xs text-secondary">Weeks Elapsed</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-6">
            {{-- Left Column: Journey Timeline --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Gantt Chart View --}}
                <div class="bg-card rounded-2xl border border-border overflow-hidden">
                    <div class="px-6 py-4 border-b border-border flex items-center justify-between bg-gradient-to-r from-surface/50 to-transparent">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-accent/10 flex items-center justify-center">
                                <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                                </svg>
                            </div>
                            <h3 class="text-sm font-semibold text-primary">Journey Timeline</h3>
                        </div>
                        <div class="flex items-center gap-2">
                            <button @click="changeViewMode('Day')" :class="viewMode === 'Day' ? 'bg-accent text-white' : 'text-secondary hover:text-primary'" class="px-3 py-1 rounded-lg text-xs font-medium transition-all">Day</button>
                            <button @click="changeViewMode('Week')" :class="viewMode === 'Week' ? 'bg-accent text-white' : 'text-secondary hover:text-primary'" class="px-3 py-1 rounded-lg text-xs font-medium transition-all">Week</button>
                            <button @click="changeViewMode('Month')" :class="viewMode === 'Month' ? 'bg-accent text-white' : 'text-secondary hover:text-primary'" class="px-3 py-1 rounded-lg text-xs font-medium transition-all">Month</button>
                        </div>
                    </div>
                    <div class="p-6" style="min-height: 400px;">
                        <div x-show="loading" class="flex items-center justify-center h-80">
                            <div class="flex flex-col items-center">
                                <svg class="w-10 h-10 text-accent animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <p class="text-sm text-secondary mt-4">Loading journey...</p>
                            </div>
                        </div>
                        <div x-show="!loading && timelineData.length === 0" class="flex flex-col items-center justify-center h-80">
                            <svg class="w-16 h-16 text-tertiary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                            </svg>
                            <h3 class="text-base font-semibold text-primary mt-4">No Journey Data</h3>
                            <p class="text-sm text-secondary mt-2">Your research journey will be displayed here.</p>
                        </div>
                        <div x-show="!loading && timelineData.length > 0" class="gantt-container">
                            <svg id="journey-gantt" class="w-full rounded-xl"></svg>
                        </div>
                    </div>
                </div>

                {{-- Stages List --}}
                <div class="bg-card rounded-2xl border border-border overflow-hidden" x-show="stages.length > 0">
                    <div class="px-6 py-4 border-b border-border flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-primary">Journey Stages</h3>
                        <span class="text-xs text-secondary" x-text="stages.length + ' stages'"></span>
                    </div>
                    <div class="divide-y divide-border max-h-96 overflow-y-auto">
                        <template x-for="stage in stages" :key="stage.id">
                            <div class="p-4 hover:bg-surface transition-colors cursor-pointer" @click="viewStageDetails(stage)">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                                         :class="{
                                             'bg-success/10': stage.status === 'completed',
                                             'bg-accent/10': stage.status === 'in_progress',
                                             'bg-tertiary/10': stage.status === 'not_started'
                                         }">
                                        <svg class="w-5 h-5" :class="{
                                             'text-success': stage.status === 'completed',
                                             'text-accent': stage.status === 'in_progress',
                                             'text-tertiary': stage.status === 'not_started'
                                         }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path x-show="stage.status === 'completed'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            <path x-show="stage.status === 'in_progress'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            <path x-show="stage.status === 'not_started'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-primary" x-text="stage.name"></p>
                                        <div class="flex items-center gap-3 mt-1">
                                            <span class="text-xs text-secondary" x-text="stage.milestones_count + ' milestones'"></span>
                                            <span class="text-tertiary">•</span>
                                            <span class="text-xs text-secondary" x-text="stage.completed_milestones + ' completed'"></span>
                                        </div>
                                    </div>
                                    <div class="w-24">
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="text-[10px]" :class="stage.progress === 100 ? 'text-success font-semibold' : 'text-secondary'" x-text="stage.progress + '%'"></span>
                                        </div>
                                        <div class="h-2 bg-border-light rounded-full overflow-hidden">
                                            <div class="h-full rounded-full transition-all duration-300"
                                                 :class="stage.progress === 100 ? 'bg-success' : (stage.progress > 0 ? 'bg-accent' : 'bg-tertiary')"
                                                 :style="'width: ' + stage.progress + '%'"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Right Column: Details & Legend --}}
            <div class="space-y-6">
                {{-- Legend Card --}}
                <div class="bg-card rounded-2xl border border-border overflow-hidden">
                    <div class="px-6 py-4 border-b border-border">
                        <h3 class="text-sm font-semibold text-primary">Legend</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-4 h-4 rounded bg-gradient-to-r from-success to-success/70"></div>
                            <span class="text-xs text-secondary">Completed Stage</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-4 h-4 rounded bg-gradient-to-r from-accent to-accent/70"></div>
                            <span class="text-xs text-secondary">In Progress Stage</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-4 h-4 rounded bg-gradient-to-r from-tertiary to-tertiary/70"></div>
                            <span class="text-xs text-secondary">Not Started</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-4 h-4 rounded-full bg-gradient-to-r from-danger to-rose-600 ring-4 ring-danger/20"></div>
                            <span class="text-xs text-secondary">Milestone</span>
                        </div>
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div class="bg-card rounded-2xl border border-border overflow-hidden">
                    <div class="px-6 py-4 border-b border-border">
                        <h3 class="text-sm font-semibold text-primary">Quick Actions</h3>
                    </div>
                    <div class="p-4 space-y-2">
                        <a href="{{ route('timeline.show', $student) }}"
                           class="flex items-center gap-3 p-3 rounded-xl text-secondary hover:text-primary hover:bg-surface transition-all group">
                            <div class="w-10 h-10 rounded-xl bg-accent/10 flex items-center justify-center group-hover:bg-accent/20">
                                <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium">View Timeline</p>
                                <p class="text-xs text-tertiary">Detailed Gantt chart</p>
                            </div>
                            <svg class="w-5 h-5 text-tertiary group-hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                        <a href="{{ route('tasks.index', $student) }}"
                           class="flex items-center gap-3 p-3 rounded-xl text-secondary hover:text-primary hover:bg-surface transition-all group">
                            <div class="w-10 h-10 rounded-xl bg-info/10 flex items-center justify-center group-hover:bg-info/20">
                                <svg class="w-5 h-5 text-info" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium">Manage Tasks</p>
                                <p class="text-xs text-tertiary">Kanban board view</p>
                            </div>
                            <svg class="w-5 h-5 text-tertiary group-hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                        <a href="{{ route('reports.index', $student) }}"
                           class="flex items-center gap-3 p-3 rounded-xl text-secondary hover:text-primary hover:bg-surface transition-all group">
                            <div class="w-10 h-10 rounded-xl bg-success/10 flex items-center justify-center group-hover:bg-success/20">
                                <svg class="w-5 h-5 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium">Progress Reports</p>
                                <p class="text-xs text-tertiary">View submissions</p>
                            </div>
                            <svg class="w-5 h-5 text-tertiary group-hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Journey Info --}}
                <div class="bg-card rounded-2xl border border-border overflow-hidden">
                    <div class="px-6 py-4 border-b border-border">
                        <h3 class="text-sm font-semibold text-primary">Journey Details</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between py-2 border-b border-border-light">
                            <span class="text-xs text-secondary">Start Date</span>
                            <span class="text-xs font-medium text-primary">{{ $journey->start_date?->format('M j, Y') ?? 'Not set' }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-border-light">
                            <span class="text-xs text-secondary">Status</span>
                            <span class="text-xs font-medium text-primary capitalize">{{ $journey->status ?? 'In Progress' }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <span class="text-xs text-secondary">Template</span>
                            <span class="text-xs font-medium text-primary">{{ $journey->template?->name ?? 'Custom' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/frappe-gantt@0.6.1/dist/frappe-gantt.min.js"></script>
    <script>
        function studentJourney(studentId, journeyId) {
            return {
                studentId: studentId,
                journeyId: journeyId,
                loading: true,
                ganttInstance: null,
                viewMode: 'Week',
                timelineData: [],
                stages: [],
                stats: {
                    overall_progress: 0,
                    total_stages: 0,
                    completed_stages: 0,
                    total_milestones: 0,
                    completed_milestones: 0,
                    elapsed_weeks: 0,
                },
                notificationCounter: 0,
                notifications: [],

                async init() {
                    await this.loadJourneyData();
                },

                async loadJourneyData() {
                    this.loading = true;
                    try {
                        const response = await fetch(`/students/${this.studentId}/journey/data`);
                        if (!response.ok) throw new Error('Failed to load journey data');

                        const data = await response.json();
                        this.timelineData = data.timeline || [];
                        this.stages = data.stages || [];
                        this.stats = data.stats || this.stats;
                        this.journey = data.journey || {};

                        this.$nextTick(() => {
                            if (this.timelineData.length > 0) {
                                this.renderGantt();
                            }
                            this.loading = false;
                        });
                    } catch (error) {
                        console.error('Error loading journey:', error);
                        this.showNotification('Failed to load journey data', 'error');
                        this.loading = false;
                    }
                },

                renderGantt() {
                    const chartElement = document.getElementById('journey-gantt');
                    if (!chartElement || this.timelineData.length === 0) return;

                    chartElement.innerHTML = '';

                    this.ganttInstance = new Gantt(chartElement, this.timelineData, {
                        header_height: 50,
                        column_width: 30,
                        step: 1,
                        view_modes: ['Day', 'Week', 'Month'],
                        bar_height: 28,
                        bar_corner_radius: 6,
                        arrow_curve: 5,
                        padding: 18,
                        view_mode: this.viewMode,
                        date_format: 'YYYY-MM-DD',
                        language: 'en',
                        custom_popup_html: (task) => this.createPopupHtml(task),
                    });

                    this.$nextTick(() => this.applyCustomStyles());
                },

                createPopupHtml(task) {
                    const isMilestone = task.custom_class === 'gantt-milestone';
                    const isStage = task.type === 'stage';
                    return `
                        <div class="gantt-popup bg-white border border-gray-200 rounded-xl shadow-xl p-4 min-w-[220px]">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-semibold text-sm text-primary">${task.name}</span>
                                ${isMilestone ? '<span class="text-[10px] px-2 py-0.5 rounded-full bg-danger/10 text-danger font-medium">Milestone</span>' : ''}
                                ${isStage ? '<span class="text-[10px] px-2 py-0.5 rounded-full bg-info/10 text-info font-medium">Stage</span>' : ''}
                            </div>
                            <div class="text-xs text-secondary space-y-1">
                                <p>Start: ${task.start}</p>
                                <p>End: ${task.end}</p>
                                <p>Progress: ${task.progress}%</p>
                            </div>
                        </div>
                    `;
                },

                applyCustomStyles() {
                    const style = document.createElement('style');
                    style.innerHTML = `
                        .gantt-stage .gantt-bar-progress {
                            border-radius: 6px;
                        }
                        .gantt-stage-completed .gantt-bar-progress { background: linear-gradient(90deg, #10B981 0%, #059669 100%) !important; }
                        .gantt-stage-in_progress .gantt-bar-progress { background: linear-gradient(90deg, #F59E0B 0%, #D97706 100%) !important; }
                        .gantt-stage-not_started .gantt-bar-progress { background: linear-gradient(90deg, #A8A29E 0%, #78716C 100%) !important; }
                        .gantt-milestone .gantt-bar-progress {
                            background: linear-gradient(135deg, #DC2626 0%, #E11D48 100%) !important;
                            border-radius: 50% !important;
                            transform: scale(1.15);
                            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.15);
                        }
                        .gantt-bar {
                            transition: all 0.2s ease;
                        }
                        .gantt-bar:hover {
                            transform: scaleY(1.05);
                            filter: brightness(1.05);
                        }
                    `;
                    document.head.appendChild(style);
                },

                changeViewMode(mode) {
                    this.viewMode = mode;
                    if (this.ganttInstance) {
                        this.ganttInstance.change_view_mode(mode);
                    }
                },

                async refreshData() {
                    await this.loadJourneyData();
                    this.showNotification('Journey data refreshed', 'success');
                },

                viewStageDetails(stage) {
                    // Navigate to tasks filtered by stage
                    window.location.href = `/students/${this.studentId}/tasks?stage=${stage.id}`;
                },

                showNotification(message, type = 'success') {
                    const id = ++this.notificationCounter;
                    this.notifications.push({ id, message, type, show: true });
                    setTimeout(() => {
                        this.dismissNotification(id);
                    }, 4000);
                },

                dismissNotification(id) {
                    this.notifications = this.notifications.filter(n => n.id !== id);
                }
            };
        }
    </script>

    <style>
        .gantt-popup {
            min-width: 220px;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            border: 1px solid #E5E5E4;
            z-index: 1000;
        }
        .gantt-container {
            background: linear-gradient(135deg, #FAFAF9 0%, #F5F5F4 100%);
            border-radius: 12px;
            padding: 16px;
        }
    </style>
</x-layouts.app>
