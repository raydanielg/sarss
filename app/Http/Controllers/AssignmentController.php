<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Panel;
use App\Models\District;
use App\Models\School;
use App\Models\Notification;
use App\Traits\Auditable;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    use Auditable;

    public function index()
    {
        $assignments = Assignment::with(['panel.examination', 'panel.subject', 'user', 'district', 'schools'])
            ->orderBy('created_at', 'desc')->get();
        return view('assignments.index', compact('assignments'));
    }

    public function create()
    {
        $panels = Panel::with(['examination', 'subject', 'dataEntries.user'])->orderBy('created_at', 'desc')->get();
        $districts = District::with('schools')->orderBy('name')->get();
        return view('assignments.create', compact('panels', 'districts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'panel_id' => 'required|exists:panels,id',
            'user_id' => 'required|exists:users,id',
            'district_id' => 'required|exists:districts,id',
            'schools' => 'required|array',
            'schools.*' => 'exists:schools,id',
        ]);
        $panel = Panel::find($data['panel_id']);
        $assignment = Assignment::create([
            'panel_id' => $data['panel_id'],
            'user_id' => $data['user_id'],
            'district_id' => $data['district_id'],
            'examination_id' => $panel->examination_id,
            'subject_id' => $panel->subject_id,
        ]);
        $assignment->schools()->sync($data['schools']);
        Notification::create([
            'user_id' => $data['user_id'],
            'title' => 'New Assignment',
            'message' => "You have been assigned to {$panel->subject->name} for " . $assignment->district->name . " district.",
            'type' => 'info',
            'link' => route('marks.entry'),
        ]);
        $this->logAction('create', 'Assignments', "Created assignment for user #{$data['user_id']}");
        return redirect()->route('assignments.index')->with('status', 'Assignment created successfully.');
    }

    public function destroy(Assignment $assignment)
    {
        $assignment->delete();
        $this->logAction('delete', 'Assignments', "Deleted assignment #{$assignment->id}");
        return redirect()->route('assignments.index')->with('status', 'Assignment deleted.');
    }
}
