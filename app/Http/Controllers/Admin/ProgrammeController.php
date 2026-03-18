<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Programme;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProgrammeController extends Controller
{
    public function index()
    {
        $programmes = Programme::withCount('students')->orderBy('sort_order')->get();
        return view('admin.programmes.index', compact('programmes'));
    }

    public function create()
    {
        return view('admin.programmes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:programmes',
            'description' => 'nullable|string',
            'duration_months' => 'required|integer|min:1',
        ]);

        Programme::create([
            ...$validated,
            'slug' => Str::slug($validated['code']),
        ]);

        return redirect()->route('admin.programmes.index')->with('success', 'Programme created.');
    }

    public function edit(Programme $programme)
    {
        return view('admin.programmes.edit', compact('programme'));
    }

    public function update(Request $request, Programme $programme)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:programmes,code,' . $programme->id,
            'description' => 'nullable|string',
            'duration_months' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $programme->update([
            ...$validated,
            'slug' => Str::slug($validated['code']),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.programmes.index')->with('success', 'Programme updated.');
    }

    public function destroy(Programme $programme)
    {
        if ($programme->students()->count() > 0) {
            return back()->with('error', 'Cannot delete programme with students.');
        }
        $programme->delete();
        return redirect()->route('admin.programmes.index')->with('success', 'Programme deleted.');
    }
}
