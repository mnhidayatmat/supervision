<x-layouts.app title="Gantt Chart">
    <x-slot:header>
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <span>Gantt Chart</span>
            <span class="text-xs text-secondary">{{ $student->user->name }}</span>
        </div>
    </x-slot:header>

    <!-- View Toggle Tabs -->
    <div class="flex items-center justify-between mb-5">
        <div class="flex items-center gap-1 bg-surface rounded-xl p-1 border border-border">
            <a href="{{ route('tasks.index', $student) }}" class="px-4 py-2 text-xs font-medium rounded-lg text-secondary hover:text-primary transition-all flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
                List
            </a>
            <a href="{{ route('tasks.kanban', $student) }}" class="px-4 py-2 text-xs font-medium rounded-lg text-secondary hover:text-primary transition-all flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                </svg>
                Kanban
            </a>
            <a href="{{ route('tasks.gantt', $student) }}" class="px-4 py-2 text-xs font-medium rounded-lg bg-accent text-white shadow-sm flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Gantt
            </a>
        </div>
    </div>

    <div x-data="initTaskFlowGantt({ studentId: {{ $student->id }} })" x-init="init()" class="space-y-4">
        <!-- Enhanced Toolbar -->
        <div class="bg-card rounded-2xl border border-border p-4">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <!-- Left: View Mode & Zoom -->
                <div class="flex flex-wrap items-center gap-3">
                    <!-- View Mode Selector -->
                    <div class="flex items-center gap-1">
                        <span class="text-xs font-medium text-secondary mr-1">View:</span>
                        <div class="flex items-center gap-0.5 bg-surface rounded-lg p-0.5 border border-border-light">
                            <button @click="setView('Day')" :class="viewMode === 'Day' ? 'bg-card text-accent shadow-sm' : 'text-secondary hover:text-primary'" class="px-2.5 py-1 text-xs font-medium rounded-md transition-all" title="Day View">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </button>
                            <button @click="setView('Week')" :class="viewMode === 'Week' ? 'bg-card text-accent shadow-sm' : 'text-secondary hover:text-primary'" class="px-2.5 py-1 text-xs font-medium rounded-md transition-all" title="Week View">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </button>
                            <button @click="setView('Month')" :class="viewMode === 'Month' ? 'bg-card text-accent shadow-sm' : 'text-secondary hover:text-primary'" class="px-2.5 py-1 text-xs font-medium rounded-md transition-all" title="Month View">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="w-px h-6 bg-border"></div>

                    <!-- Navigation Controls -->
                    <div class="flex items-center gap-0.5 bg-surface rounded-lg p-0.5 border border-border-light">
                        <button @click="navigate('prev')" class="p-1.5 rounded-md text-secondary hover:text-primary hover:bg-card transition-all" title="Previous">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <button @click="navigate('today')" class="px-2 py-1 text-xs font-medium rounded-md text-secondary hover:text-primary hover:bg-card transition-all">Today</button>
                        <button @click="navigate('next')" class="p-1.5 rounded-md text-secondary hover:text-primary hover:bg-card transition-all" title="Next">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Divider -->
                    <div class="w-px h-6 bg-border"></div>

                    <!-- Zoom Controls -->
                    <div class="flex items-center gap-0.5 bg-surface rounded-lg p-0.5 border border-border-light">
                        <button @click="zoom('out')" class="p-1.5 rounded-md text-secondary hover:text-primary hover:bg-card transition-all" title="Zoom Out">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                            </svg>
                        </button>
                        <button @click="zoom('reset')" class="px-2 py-1 text-xs font-medium rounded-md text-secondary hover:text-primary hover:bg-card transition-all">Fit</button>
                        <button @click="zoom('in')" class="p-1.5 rounded-md text-secondary hover:text-primary hover:bg-card transition-all" title="Zoom In">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Right: Actions & Legend -->
                <div class="flex flex-wrap items-center gap-3">
                    <!-- Compact Legend -->
                    <div class="hidden xl:flex items-center gap-1.5">
                        <span class="text-xs text-secondary">Status:</span>
                        <div class="flex items-center gap-1">
                            <span class="flex items-center gap-1 px-2 py-0.5 rounded-full bg-surface text-xs text-secondary hover:bg-card cursor-pointer transition-colors" title="Backlog">
                                <span class="w-1.5 h-1.5 rounded-full bg-tertiary"></span>
                            </span>
                            <span class="flex items-center gap-1 px-2 py-0.5 rounded-full bg-surface text-xs text-secondary hover:bg-card cursor-pointer transition-colors" title="Planned">
                                <span class="w-1.5 h-1.5 rounded-full bg-info"></span>
                            </span>
                            <span class="flex items-center gap-1 px-2 py-0.5 rounded-full bg-surface text-xs text-secondary hover:bg-card cursor-pointer transition-colors" title="In Progress">
                                <span class="w-1.5 h-1.5 rounded-full bg-warning"></span>
                            </span>
                            <span class="flex items-center gap-1 px-2 py-0.5 rounded-full bg-surface text-xs text-secondary hover:bg-card cursor-pointer transition-colors" title="Review">
                                <span class="w-1.5 h-1.5 rounded-full bg-accent"></span>
                            </span>
                            <span class="flex items-center gap-1 px-2 py-0.5 rounded-full bg-surface text-xs text-secondary hover:bg-card cursor-pointer transition-colors" title="Revision">
                                <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                            </span>
                            <span class="flex items-center gap-1 px-2 py-0.5 rounded-full bg-surface text-xs text-secondary hover:bg-card cursor-pointer transition-colors" title="Completed">
                                <span class="w-1.5 h-1.5 rounded-full bg-success"></span>
                            </span>
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="hidden xl:block w-px h-6 bg-border"></div>

                    <!-- Export Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" @click.away="open = false" class="flex items-center gap-1.5 px-3 py-2 text-xs font-medium rounded-lg text-secondary hover:text-primary border border-border hover:bg-surface transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Export
                            <svg class="w-3 h-3 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-40 bg-card rounded-xl border border-border shadow-lg py-1 z-50">
                            <button @click="exportAs('png'); open = false" class="w-full px-3 py-2 text-left text-xs text-secondary hover:text-primary hover:bg-surface flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Export as PNG
                            </button>
                            <button @click="exportAs('pdf'); open = false" class="w-full px-3 py-2 text-left text-xs text-secondary hover:text-primary hover:bg-surface flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                Export as PDF
                            </button>
                        </div>
                    </div>

                    <!-- View Options Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" @click.away="open = false" class="flex items-center gap-1.5 px-3 py-2 text-xs font-medium rounded-lg text-secondary hover:text-primary border border-border hover:bg-surface transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            View
                            <svg class="w-3 h-3 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-44 bg-card rounded-xl border border-border shadow-lg py-1.5 z-50">
                            <label class="flex items-center gap-2 px-3 py-2 text-xs text-secondary hover:bg-surface cursor-pointer">
                                <input type="checkbox" x-model="showDependencies" class="w-3.5 h-3.5 rounded border-border text-accent focus:ring-accent/20">
                                Show Dependencies
                            </label>
                            <label class="flex items-center gap-2 px-3 py-2 text-xs text-secondary hover:bg-surface cursor-pointer">
                                <input type="checkbox" x-model="showProgress" class="w-3.5 h-3.5 rounded border-border text-accent focus:ring-accent/20">
                                Show Progress
                            </label>
                            <label class="flex items-center gap-2 px-3 py-2 text-xs text-secondary hover:bg-surface cursor-pointer">
                                <input type="checkbox" x-model="criticalPath" class="w-3.5 h-3.5 rounded border-border text-accent focus:ring-accent/20">
                                Critical Path Only
                            </label>
                        </div>
                    </div>

                    <!-- Refresh Button -->
                    <button @click="refresh()" class="p-2 text-secondary hover:text-primary hover:bg-surface rounded-xl transition-all" title="Refresh">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </button>

                    <!-- New Task Button -->
                    <x-button href="{{ route('tasks.create', $student) }}" variant="accent" size="sm" class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        New Task
                    </x-button>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div x-show="loading" class="flex flex-col items-center justify-center py-20">
            <div class="relative">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-accent/20 to-accent/5 flex items-center justify-center">
                    <svg class="w-6 h-6 text-accent animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-secondary mt-4">Loading timeline...</p>
        </div>

        <!-- Gantt Container -->
        <div x-show="!loading" class="bg-card rounded-2xl border border-border overflow-hidden shadow-soft">
            <div id="gantt-container" class="min-h-[500px]"></div>
        </div>

        <!-- Instructions & Stats -->
        <div x-show="!loading" class="flex flex-col sm:flex-row items-center justify-between gap-4 px-2">
            <div class="flex items-center gap-4 text-xs text-secondary">
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Drag task ends to adjust dates
                </span>
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/>
                    </svg>
                    Click task to view details
                </span>
            </div>

            <!-- Quick Stats -->
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-success/10 text-success text-xs font-medium">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span x-text="taskStats.completed || 0">0</span> Completed
                </div>
                <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-warning/10 text-warning text-xs font-medium">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span x-text="taskStats.inProgress || 0">0</span> In Progress
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/frappe-gantt@0.6.1/dist/frappe-gantt.min.css">
    @endpush
</x-layouts.app>
