<?php

namespace App\Http\Controllers;

use App\Models\Mark;
use App\Models\Assignment;
use App\Models\Candidate;
use App\Models\Examination;
use App\Models\Subject;
use App\Models\Notification;
use App\Traits\Auditable;
use Illuminate\Http\Request;

class MarkController extends Controller
{
    use Auditable;

    public function entry(Request $request)
    {
        $user = auth()->user();
        $assignments = Assignment::with(['panel.examination', 'panel.subject', 'district', 'schools'])
            ->where('user_id', $user->id)->get();

        if ($request->assignment_id) {
            $assignment = Assignment::with(['panel.examination', 'panel.subject', 'district', 'schools.district'])
                ->where('user_id', $user->id)->findOrFail($request->assignment_id);
            $schoolId = $request->school_id ?? $assignment->schools->first()?->id;

            if ($schoolId) {
                $candidates = Candidate::where('examination_id', $assignment->examination_id)
                    ->where('school_id', $schoolId)
                    ->orderBy('candidate_number')->get();
                $marks = Mark::where('examination_id', $assignment->examination_id)
                    ->where('subject_id', $assignment->subject_id)
                    ->where('school_id', $schoolId)
                    ->get()->keyBy('candidate_id');
                $subject = $assignment->panel->subject;
                $school = \App\Models\School::find($schoolId);
                return view('marks.entry', compact('assignment', 'school', 'candidates', 'marks', 'subject'));
            }
        }

        return view('marks.entry', compact('assignments'));
    }

    public function save(Request $request)
    {
        $request->validate([
            'assignment_id' => 'required|exists:assignments,id',
            'school_id' => 'required|exists:schools,id',
            'marks' => 'required|array',
            'marks.*.candidate_id' => 'required|exists:candidates,id',
            'marks.*.mark' => 'nullable|numeric|min:0',
        ]);
        $assignment = Assignment::where('user_id', auth()->id())->findOrFail($request->assignment_id);
        $subject = $assignment->panel->subject;
        $saved = 0;
        foreach ($request->marks as $item) {
            if ($item['mark'] === null || $item['mark'] === '') continue;
            Mark::updateOrCreate(
                [
                    'examination_id' => $assignment->examination_id,
                    'candidate_id' => $item['candidate_id'],
                    'subject_id' => $assignment->subject_id,
                    'school_id' => $request->school_id,
                ],
                [
                    'mark' => $item['mark'],
                    'status' => 'entered',
                    'entered_by' => auth()->id(),
                    'entered_at' => now(),
                ]
            );
            $saved++;
        }
        $this->logAction('entry', 'Marks', "Entered {$saved} marks for school #{$request->school_id}");
        return back()->with('status', "{$saved} marks saved successfully.");
    }

    public function autoSave(Request $request)
    {
        $request->validate([
            'assignment_id' => 'required|exists:assignments,id',
            'school_id' => 'required|exists:schools,id',
            'candidate_id' => 'required|exists:candidates,id',
            'mark' => 'nullable|numeric|min:0',
        ]);

        $assignment = Assignment::where('user_id', auth()->id())->findOrFail($request->assignment_id);
        $subject = $assignment->panel->subject;

        if ($request->mark !== null && $request->mark !== '' && $request->mark > $subject->max_marks) {
            return response()->json(['success' => false, 'message' => 'Mark exceeds maximum of ' . $subject->max_marks], 422);
        }

        $existing = Mark::where('examination_id', $assignment->examination_id)
            ->where('candidate_id', $request->candidate_id)
            ->where('subject_id', $assignment->subject_id)
            ->where('school_id', $request->school_id)
            ->first();

        if ($existing && in_array($existing->status, ['verified', 'locked'])) {
            return response()->json(['success' => false, 'message' => 'Mark is ' . $existing->status . ' and cannot be edited'], 422);
        }

        $markValue = ($request->mark === null || $request->mark === '') ? null : $request->mark;
        $status = $markValue === null ? 'pending' : 'entered';

        Mark::updateOrCreate(
            [
                'examination_id' => $assignment->examination_id,
                'candidate_id' => $request->candidate_id,
                'subject_id' => $assignment->subject_id,
                'school_id' => $request->school_id,
            ],
            [
                'mark' => $markValue,
                'status' => $status,
                'entered_by' => auth()->id(),
                'entered_at' => $markValue !== null ? now() : null,
            ]
        );

        $total = Candidate::where('examination_id', $assignment->examination_id)
            ->where('school_id', $request->school_id)->count();
        $entered = Mark::where('examination_id', $assignment->examination_id)
            ->where('subject_id', $assignment->subject_id)
            ->where('school_id', $request->school_id)
            ->whereNotNull('mark')->count();

        return response()->json([
            'success' => true,
            'entered' => $entered,
            'total' => $total,
            'percentage' => $total > 0 ? round($entered / $total * 100, 1) : 0,
        ]);
    }

    public function myProgress()
    {
        $user = auth()->user();
        $assignments = Assignment::with(['panel.examination', 'panel.subject', 'district', 'schools'])
            ->where('user_id', $user->id)->get();
        $progress = [];
        foreach ($assignments as $assignment) {
            foreach ($assignment->schools as $school) {
                $total = Candidate::where('examination_id', $assignment->examination_id)
                    ->where('school_id', $school->id)->count();
                $entered = Mark::where('examination_id', $assignment->examination_id)
                    ->where('subject_id', $assignment->subject_id)
                    ->where('school_id', $school->id)
                    ->whereNotNull('mark')->count();
                $progress[] = [
                    'assignment' => $assignment,
                    'school' => $school,
                    'total' => $total,
                    'entered' => $entered,
                    'percentage' => $total > 0 ? round($entered / $total * 100, 1) : 0,
                ];
            }
        }
        return view('marks.progress', compact('progress'));
    }
}
