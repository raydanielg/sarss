<?php

namespace App\Http\Controllers;

use App\Models\Panel;
use App\Models\Mark;
use App\Models\Notification;
use App\Traits\Auditable;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    use Auditable;

    public function index()
    {
        $user = auth()->user();
        $panels = Panel::where('moderator_user_id', $user->id)
            ->with(['examination', 'subject', 'assignments.user', 'assignments.district', 'assignments.schools'])
            ->orderBy('created_at', 'desc')->get();
        return view('verification.index', compact('panels'));
    }

    public function show(Panel $panel)
    {
        $panel->load(['examination', 'subject', 'assignments.schools.district', 'assignments.user']);
        $marks = Mark::where('examination_id', $panel->examination_id)
            ->where('subject_id', $panel->subject_id)
            ->with(['candidate', 'school', 'enteredBy'])
            ->orderBy('status')->orderBy('entered_at', 'desc')
            ->paginate(50);
        $stats = [
            'total' => Mark::where('examination_id', $panel->examination_id)->where('subject_id', $panel->subject_id)->count(),
            'entered' => Mark::where('examination_id', $panel->examination_id)->where('subject_id', $panel->subject_id)->where('status', 'entered')->count(),
            'verified' => Mark::where('examination_id', $panel->examination_id)->where('subject_id', $panel->subject_id)->where('status', 'verified')->count(),
            'rejected' => Mark::where('examination_id', $panel->examination_id)->where('subject_id', $panel->subject_id)->where('status', 'rejected')->count(),
            'pending' => Mark::where('examination_id', $panel->examination_id)->where('subject_id', $panel->subject_id)->where('status', 'pending')->count(),
        ];
        return view('verification.show', compact('panel', 'marks', 'stats'));
    }

    public function approve(Mark $mark)
    {
        $mark->update([
            'status' => 'verified',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);
        $this->logAction('verify', 'Marks', "Verified mark for candidate {$mark->candidate_id}");
        if ($mark->entered_by) {
            Notification::create([
                'user_id' => $mark->entered_by,
                'title' => 'Marks Approved',
                'message' => "Your marks for candidate {$mark->candidate?->candidate_number} have been approved.",
                'type' => 'success',
            ]);
        }
        return back()->with('status', 'Marks approved.');
    }

    public function reject(Request $request, Mark $mark)
    {
        $request->validate(['rejection_reason' => 'required|string']);
        $mark->update([
            'status' => 'rejected',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);
        $this->logAction('reject', 'Marks', "Rejected mark for candidate {$mark->candidate_id}: {$request->rejection_reason}");
        if ($mark->entered_by) {
            Notification::create([
                'user_id' => $mark->entered_by,
                'title' => 'Marks Rejected',
                'message' => "Marks for candidate {$mark->candidate?->candidate_number} have been rejected. Reason: {$request->rejection_reason}",
                'type' => 'error',
            ]);
        }
        return back()->with('status', 'Marks rejected and returned for correction.');
    }

    public function bulkApprove(Panel $panel)
    {
        $count = Mark::where('examination_id', $panel->examination_id)
            ->where('subject_id', $panel->subject_id)
            ->where('status', 'entered')
            ->update([
                'status' => 'verified',
                'verified_by' => auth()->id(),
                'verified_at' => now(),
            ]);
        $this->logAction('bulk_verify', 'Marks', "Bulk approved {$count} marks for panel #{$panel->id}");
        return back()->with('status', "{$count} marks approved.");
    }
}
