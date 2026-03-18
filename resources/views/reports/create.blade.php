<x-layouts.app title="Create Report">
    <x-slot:header>Create Report</x-slot:header>

    <div class="max-w-2xl">
        <x-card>
            <form method="POST" action="{{ route('reports.store', $student) }}" class="space-y-4">
                @csrf
                <x-input name="title" label="Title" required placeholder="e.g. Week 12 Progress Report" />
                <x-select name="type" label="Report Type" required :options="['weekly' => 'Weekly', 'monthly' => 'Monthly', 'milestone' => 'Milestone', 'custom' => 'Custom']" />

                <div class="grid sm:grid-cols-2 gap-4">
                    <x-input name="period_start" type="date" label="Period Start" />
                    <x-input name="period_end" type="date" label="Period End" />
                </div>

                <x-textarea name="content" label="Report Content" required rows="6" placeholder="Describe your progress during this period..." />
                <x-textarea name="achievements" label="Key Achievements" rows="3" placeholder="What did you accomplish?" />
                <x-textarea name="challenges" label="Challenges" rows="3" placeholder="What challenges did you face?" />
                <x-textarea name="next_steps" label="Next Steps" rows="3" placeholder="What are your plans for the next period?" />

                <div class="flex items-center gap-3 pt-2">
                    <x-button type="submit" name="save" variant="secondary">Save Draft</x-button>
                    <x-button type="submit" name="submit" value="1" variant="accent">Submit to Supervisor</x-button>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>
