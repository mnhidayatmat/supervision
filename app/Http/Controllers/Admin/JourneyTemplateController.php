<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JourneyTemplate;
use App\Models\Programme;
use Illuminate\Http\Request;

class JourneyTemplateController extends Controller
{
    public function index()
    {
        $templates = JourneyTemplate::with(['programme', 'stages.milestones'])->latest()->get();
        return view('admin.templates.index', compact('templates'));
    }

    public function create()
    {
        $programmes = Programme::where('is_active', true)->get();
        return view('admin.templates.create', compact('programmes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'programme_id' => 'required|exists:programmes,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_default' => 'boolean',
            'stages' => 'required|array|min:1',
            'stages.*.name' => 'required|string',
            'stages.*.duration_weeks' => 'nullable|integer',
            'stages.*.milestones' => 'nullable|array',
            'stages.*.milestones.*.name' => 'required|string',
            'stages.*.milestones.*.week_offset' => 'nullable|integer',
        ]);

        // If setting as default, unset others
        if ($request->boolean('is_default')) {
            JourneyTemplate::where('programme_id', $validated['programme_id'])->update(['is_default' => false]);
        }

        $template = JourneyTemplate::create([
            'programme_id' => $validated['programme_id'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_default' => $request->boolean('is_default'),
        ]);

        foreach ($validated['stages'] as $i => $stageData) {
            $stage = $template->stages()->create([
                'name' => $stageData['name'],
                'sort_order' => $i,
                'duration_weeks' => $stageData['duration_weeks'] ?? null,
            ]);

            if (!empty($stageData['milestones'])) {
                foreach ($stageData['milestones'] as $j => $milestoneData) {
                    $stage->milestones()->create([
                        'name' => $milestoneData['name'],
                        'sort_order' => $j,
                        'week_offset' => $milestoneData['week_offset'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('admin.templates.index')->with('success', 'Journey template created.');
    }

    public function show(JourneyTemplate $template)
    {
        $template->load('stages.milestones', 'programme');
        return view('admin.templates.show', compact('template'));
    }

    public function edit(JourneyTemplate $template)
    {
        $template->load('stages.milestones');
        $programmes = Programme::where('is_active', true)->get();
        return view('admin.templates.edit', compact('template', 'programmes'));
    }

    public function update(Request $request, JourneyTemplate $template)
    {
        $validated = $request->validate([
            'programme_id' => 'required|exists:programmes,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_default' => 'boolean',
        ]);

        // If setting as default, unset others
        if ($request->boolean('is_default')) {
            JourneyTemplate::where('programme_id', $validated['programme_id'])
                ->where('id', '!=', $template->id)
                ->update(['is_default' => false]);
        }

        $template->update([
            'programme_id' => $validated['programme_id'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_default' => $request->boolean('is_default'),
        ]);

        return redirect()->route('admin.templates.show', $template)->with('success', 'Template updated.');
    }

    public function destroy(JourneyTemplate $template)
    {
        $template->delete();
        return redirect()->route('admin.templates.index')->with('success', 'Template deleted.');
    }
}
