<?php

namespace App\Http\Controllers;

use App\Models\Examination;
use App\Models\Candidate;
use App\Models\Mark;
use App\Models\User;
use App\Models\Panel;
use App\Models\Notification;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();

        if ($user->force_password_change) {
            return view('auth.force-password');
        }

        $stats = [];
        $recentExams = collect();
        $notifications = collect();

        if ($user->hasAnyRole(['super_admin', 'exam_admin', 'viewer'])) {
            $stats = [
                'examinations' => Examination::count(),
                'active_exams' => Examination::where('status', 'open')->count(),
                'candidates' => Candidate::count(),
                'schools' => \App\Models\School::count(),
                'subjects' => \App\Models\Subject::count(),
                'users' => User::count(),
                'marks_entered' => Mark::whereNotNull('mark')->count(),
                'marks_verified' => Mark::where('status', 'verified')->count(),
            ];
            $recentExams = Examination::with(['academicYear', 'examType'])
                ->orderBy('created_at', 'desc')->take(5)->get();
        } elseif ($user->hasRole('moderator')) {
            $panels = Panel::where('moderator_user_id', $user->id)->pluck('id');
            $examIds = Panel::where('moderator_user_id', $user->id)->pluck('examination_id');
            $stats = [
                'panels' => $panels->count(),
                'marks_pending' => Mark::whereIn('examination_id', $examIds)->where('status', 'entered')->count(),
                'marks_verified' => Mark::whereIn('examination_id', $examIds)->where('status', 'verified')->count(),
                'marks_rejected' => Mark::whereIn('examination_id', $examIds)->where('status', 'rejected')->count(),
            ];
            $recentExams = Examination::whereIn('id', $examIds)->orderBy('created_at', 'desc')->take(5)->get();
        } elseif ($user->hasRole('data_entry')) {
            $assignments = \App\Models\Assignment::where('user_id', $user->id)->get();
            $examIds = $assignments->pluck('examination_id')->unique();
            $totalEntered = Mark::where('entered_by', $user->id)->whereNotNull('mark')->count();
            $stats = [
                'assignments' => $assignments->count(),
                'schools_assigned' => \App\Models\Assignment::where('user_id', $user->id)
                    ->with('schools')->get()->pluck('schools')->flatten()->unique('id')->count(),
                'marks_entered' => $totalEntered,
                'examinations' => $examIds->count(),
            ];
            $recentExams = Examination::whereIn('id', $examIds)->orderBy('created_at', 'desc')->take(5)->get();
        }

        $notifications = Notification::where('user_id', $user->id)
            ->whereNull('read_at')->orderBy('created_at', 'desc')->take(5)->get();

        // Login activities (recent logins across system for admin, own logins for others)
        $loginActivities = \App\Models\AuditLog::with('user')
            ->where('action', 'login')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        // Chart data for admin/exam_admin/viewer
        $chartData = [];
        if ($user->hasAnyRole(['super_admin', 'exam_admin', 'viewer'])) {
            // Marks progress by subject (bar chart)
            $subjects = \App\Models\Subject::orderBy('name')->take(6)->get();
            $subjectLabels = [];
            $subjectEntered = [];
            $subjectVerified = [];
            foreach ($subjects as $sub) {
                $subjectLabels[] = $sub->name;
                $subjectEntered[] = Mark::where('subject_id', $sub->id)->whereNotNull('mark')->count();
                $subjectVerified[] = Mark::where('subject_id', $sub->id)->where('status', 'verified')->count();
            }

            // Exam status distribution (donut chart)
            $examStatuses = [
                'draft' => Examination::where('status', 'draft')->count(),
                'open' => Examination::where('status', 'open')->count(),
                'closed' => Examination::where('status', 'closed')->count(),
                'archived' => Examination::where('status', 'archived')->count(),
            ];

            // Overall marks progress (circular)
            $totalMarks = Mark::count();
            $enteredMarks = Mark::whereNotNull('mark')->count();
            $verifiedMarks = Mark::where('status', 'verified')->count();

            $chartData = [
                'subject_labels' => $subjectLabels,
                'subject_entered' => $subjectEntered,
                'subject_verified' => $subjectVerified,
                'exam_statuses' => $examStatuses,
                'total_marks' => $totalMarks,
                'entered_marks' => $enteredMarks,
                'verified_marks' => $verifiedMarks,
                'entry_pct' => $totalMarks > 0 ? round($enteredMarks / $totalMarks * 100, 1) : 0,
                'verification_pct' => $enteredMarks > 0 ? round($verifiedMarks / $enteredMarks * 100, 1) : 0,
            ];
        } elseif ($user->hasRole('moderator')) {
            $panels = Panel::where('moderator_user_id', $user->id)->pluck('id');
            $examIds = Panel::where('moderator_user_id', $user->id)->pluck('examination_id');
            $totalMarks = Mark::whereIn('examination_id', $examIds)->count();
            $enteredMarks = Mark::whereIn('examination_id', $examIds)->whereNotNull('mark')->count();
            $verifiedMarks = Mark::whereIn('examination_id', $examIds)->where('status', 'verified')->count();

            $chartData = [
                'total_marks' => $totalMarks,
                'entered_marks' => $enteredMarks,
                'verified_marks' => $verifiedMarks,
                'entry_pct' => $totalMarks > 0 ? round($enteredMarks / $totalMarks * 100, 1) : 0,
                'verification_pct' => $enteredMarks > 0 ? round($verifiedMarks / $enteredMarks * 100, 1) : 0,
            ];
        } elseif ($user->hasRole('data_entry')) {
            $totalEntered = Mark::where('entered_by', $user->id)->whereNotNull('mark')->count();
            $verifiedMarks = Mark::where('entered_by', $user->id)->where('status', 'verified')->count();
            $chartData = [
                'entered_marks' => $totalEntered,
                'verified_marks' => $verifiedMarks,
                'entry_pct' => 100,
                'verification_pct' => $totalEntered > 0 ? round($verifiedMarks / $totalEntered * 100, 1) : 0,
            ];
        }

        return view('home', compact('stats', 'recentExams', 'notifications', 'loginActivities', 'chartData'));
    }

    public function forcePassword()
    {
        return view('auth.force-password');
    }
}
