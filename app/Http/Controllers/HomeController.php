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

        return view('home', compact('stats', 'recentExams', 'notifications'));
    }

    public function forcePassword()
    {
        return view('auth.force-password');
    }
}
