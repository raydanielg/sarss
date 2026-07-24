<?php

namespace App\Http\Controllers;

use App\Models\Examination;
use App\Models\AcademicYear;
use App\Models\ExamType;
use App\Models\Region;
use App\Models\District;
use App\Models\School;
use App\Models\Subject;
use App\Models\Classes;
use App\Models\Notification;
use App\Traits\Auditable;
use Illuminate\Http\Request;

class ExaminationController extends Controller
{
    use Auditable;

    public function index()
    {
        $examinations = Examination::with(['academicYear', 'examType', 'region', 'creator'])
            ->withCount(['candidates', 'panels', 'schools', 'subjects'])
            ->orderBy('created_at', 'desc')->get();
        return view('examinations.index', compact('examinations'));
    }

    public function create()
    {
        $academicYears = AcademicYear::where('is_active', true)->orderBy('year', 'desc')->get();
        $examTypes = ExamType::orderBy('name')->get();
        $regions = Region::orderBy('name')->get();
        $districts = District::with('region')->orderBy('name')->get();
        $schools = School::with('district')->orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $classes = Classes::orderBy('level')->get();
        return view('examinations.create', compact('academicYears', 'examTypes', 'regions', 'districts', 'schools', 'subjects', 'classes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'academic_year_id' => 'required|exists:academic_years,id',
            'exam_type_id' => 'required|exists:exam_types,id',
            'region_id' => 'required|exists:regions,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'districts' => 'required|array',
            'districts.*' => 'exists:districts,id',
            'schools' => 'required|array',
            'schools.*' => 'exists:schools,id',
            'subjects' => 'required|array',
            'subjects.*' => 'exists:subjects,id',
            'classes' => 'required|array',
            'classes.*' => 'exists:classes,id',
        ]);
        $data['created_by'] = auth()->id();
        $data['status'] = 'draft';
        $districts = $data['districts'];
        $schools = $data['schools'];
        $subjects = $data['subjects'];
        $classes = $data['classes'];
        unset($data['districts'], $data['schools'], $data['subjects'], $data['classes']);
        $exam = Examination::create($data);
        $exam->districts()->sync($districts);
        $exam->schools()->sync($schools);
        $exam->subjects()->sync($subjects);
        $exam->classes()->sync($classes);
        $this->logAction('create', 'Examinations', "Created examination {$exam->name}");
        return redirect()->route('examinations.index')->with('status', 'Examination created successfully.');
    }

    public function show(Examination $examination)
    {
        $examination->load(['academicYear', 'examType', 'region', 'districts', 'schools', 'subjects', 'classes', 'panels.subject', 'panels.moderator']);
        $stats = [
            'schools' => $examination->schools()->count(),
            'candidates' => $examination->candidates()->count(),
            'subjects' => $examination->subjects()->count(),
            'panels' => $examination->panels()->count(),
            'markers' => \App\Models\PanelMarker::whereHas('panel', fn($q) => $q->where('examination_id', $examination->id))->count(),
            'data_entries' => \App\Models\PanelDataEntry::whereHas('panel', fn($q) => $q->where('examination_id', $examination->id))->count(),
            'marks_entered' => $examination->marks()->where('status', 'entered')->orWhere('status', 'verified')->count(),
            'marks_verified' => $examination->marks()->where('status', 'verified')->count(),
            'total_marks' => $examination->marks()->count(),
        ];
        return view('examinations.show', compact('examination', 'stats'));
    }

    public function edit(Examination $examination)
    {
        $academicYears = AcademicYear::orderBy('year', 'desc')->get();
        $examTypes = ExamType::orderBy('name')->get();
        $regions = Region::orderBy('name')->get();
        $districts = District::with('region')->orderBy('name')->get();
        $schools = School::with('district')->orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $classes = Classes::orderBy('level')->get();
        $examination->load(['districts', 'schools', 'subjects', 'classes']);
        return view('examinations.edit', compact('examination', 'academicYears', 'examTypes', 'regions', 'districts', 'schools', 'subjects', 'classes'));
    }

    public function update(Request $request, Examination $examination)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'academic_year_id' => 'required|exists:academic_years,id',
            'exam_type_id' => 'required|exists:exam_types,id',
            'region_id' => 'required|exists:regions,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'status' => 'required|in:draft,open,closed,archived',
            'districts' => 'array',
            'districts.*' => 'exists:districts,id',
            'schools' => 'array',
            'schools.*' => 'exists:schools,id',
            'subjects' => 'array',
            'subjects.*' => 'exists:subjects,id',
            'classes' => 'array',
            'classes.*' => 'exists:classes,id',
        ]);
        $old = $examination->toArray();
        $districts = $data['districts'] ?? [];
        $schools = $data['schools'] ?? [];
        $subjects = $data['subjects'] ?? [];
        $classes = $data['classes'] ?? [];
        unset($data['districts'], $data['schools'], $data['subjects'], $data['classes']);
        $examination->update($data);
        $examination->districts()->sync($districts);
        $examination->schools()->sync($schools);
        $examination->subjects()->sync($subjects);
        $examination->classes()->sync($classes);
        $this->logAction('update', 'Examinations', "Updated examination {$examination->name}", $old, $examination->toArray());
        return redirect()->route('examinations.index')->with('status', 'Examination updated successfully.');
    }

    public function changeStatus(Examination $examination, $status)
    {
        if (!in_array($status, ['draft', 'open', 'closed', 'archived'])) {
            return back()->with('error', 'Invalid status.');
        }
        $old = $examination->status;
        $examination->update(['status' => $status]);
        $this->logAction('status_change', 'Examinations', "Changed examination {$examination->name} status from {$old} to {$status}");
        Notification::create([
            'user_id' => auth()->id(),
            'title' => 'Examination Status Changed',
            'message' => "{$examination->name} is now {$status}",
            'type' => 'info',
        ]);
        return back()->with('status', "Examination status changed to {$status}.");
    }

    public function destroy(Examination $examination)
    {
        $examination->delete();
        $this->logAction('delete', 'Examinations', "Deleted examination {$examination->name}");
        return redirect()->route('examinations.index')->with('status', 'Examination deleted.');
    }
}
