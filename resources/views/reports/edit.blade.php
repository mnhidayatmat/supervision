<x-layouts.app title="Edit Report">
    <x-slot:header>Edit Report</x-slot:header>

    <div class="max-w-2xl">
        <x-card>
            <form method="POST" action="{{ route('reports.update', [$student, $report]) }}" class="space-y-4">
                @csrf @method('PUT')
                <x-input name="title" label="Title" required :value="$report->title" />
                <x-select name="type" label="Report Type" required :options="['weekly' => 'Weekly', 'monthly' => 'Monthly', 'milestone' => 'Milestone', 'custom' => 'Custom']" :selected="$report->type" />

                <div class="grid sm:grid-cols-2 gap-4">
                    <x-input name="period_start" type="date" label="Period Start" :value="$report->period_start?->format('Y-m-d')" />
                    <x-input name="period_end" type="date" label="Period End" :value="$report->period_end?->format('Y-m-d')" />
                </div>

                <x-textarea name="content" label="Report Content" required rows="6">{{ $report->content }}</x-textarea>
                <x-textarea name="achievements" label="Key Achievements" rows="3">{{ $report->achievements }}</x-textarea>
                <x-textarea name="challenges" label="Challenges" rows="3">{{ $report->challenges }}</x-textarea>
                <x-textarea name="next_steps" label="Next Steps" rows="3">{{ $report->next_steps }}</x-textarea>

                <div class="flex items-center gap-3 pt-2">
                    <x-button type="submit" name="save" variant="secondary">Save Draft</x-button>
                    <x-button type="submit" name="submit" value="1" variant="accent">Submit to Supervisor</x-button>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>
