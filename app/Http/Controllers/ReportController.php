<?php

namespace App\Http\Controllers;

use App\Models\Examination;
use App\Models\Mark;
use App\Models\Candidate;
use App\Models\School;
use App\Models\Subject;
use App\Models\District;
use App\Models\User;
use App\Models\Panel;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $examinations = Examination::withCount(['candidates', 'schools', 'subjects', 'panels'])->orderBy('created_at', 'desc')->get();
        return view('reports.index', compact('examinations'));
    }

    public function overall(Examination $examination)
    {
        $examination->load(['academicYear', 'examType', 'region']);
        $totalCandidates = $examination->candidates()->count();
        $totalSchools = $examination->schools()->count();
        $totalSubjects = $examination->subjects()->count();
        $totalMarks = $examination->marks()->count();
        $enteredMarks = $examination->marks()->whereNotNull('mark')->count();
        $verifiedMarks = $examination->marks()->where('status', 'verified')->count();
        $completionRate = $totalMarks > 0 ? round($enteredMarks / $totalMarks * 100, 1) : 0;
        $verificationRate = $enteredMarks > 0 ? round($verifiedMarks / $enteredMarks * 100, 1) : 0;
        return view('reports.overall', compact('examination', 'totalCandidates', 'totalSchools', 'totalSubjects', 'totalMarks', 'enteredMarks', 'verifiedMarks', 'completionRate', 'verificationRate'));
    }

    public function bySchool(Examination $examination)
    {
        $schools = $examination->schools()->with(['district'])->get();
        $data = [];
        foreach ($schools as $school) {
            $total = Candidate::where('examination_id', $examination->id)->where('school_id', $school->id)->count();
            $entered = Mark::where('examination_id', $examination->id)->where('school_id', $school->id)->whereNotNull('mark')->count();
            $verified = Mark::where('examination_id', $examination->id)->where('school_id', $school->id)->where('status', 'verified')->count();
            $data[] = [
                'school' => $school,
                'candidates' => $total,
                'entered' => $entered,
                'verified' => $verified,
                'pending' => $total * $examination->subjects()->count() - $entered,
                'percentage' => $total > 0 ? round($entered / ($total * $examination->subjects()->count()) * 100, 1) : 0,
            ];
        }
        return view('reports.school', compact('examination', 'data'));
    }

    public function bySubject(Examination $examination)
    {
        $subjects = $examination->subjects()->get();
        $data = [];
        foreach ($subjects as $subject) {
            $total = Mark::where('examination_id', $examination->id)->where('subject_id', $subject->id)->count();
            $entered = Mark::where('examination_id', $examination->id)->where('subject_id', $subject->id)->whereNotNull('mark')->count();
            $verified = Mark::where('examination_id', $examination->id)->where('subject_id', $subject->id)->where('status', 'verified')->count();
            $rejected = Mark::where('examination_id', $examination->id)->where('subject_id', $subject->id)->where('status', 'rejected')->count();
            $data[] = compact('subject', 'total', 'entered', 'verified', 'rejected');
        }
        return view('reports.subject', compact('examination', 'data'));
    }

    public function byDistrict(Examination $examination)
    {
        $districts = $examination->districts()->withCount(['schools'])->get();
        $data = [];
        foreach ($districts as $district) {
            $schoolIds = School::where('district_id', $district->id)->pluck('id');
            $total = Mark::where('examination_id', $examination->id)->whereIn('school_id', $schoolIds)->count();
            $entered = Mark::where('examination_id', $examination->id)->whereIn('school_id', $schoolIds)->whereNotNull('mark')->count();
            $verified = Mark::where('examination_id', $examination->id)->whereIn('school_id', $schoolIds)->where('status', 'verified')->count();
            $data[] = compact('district', 'total', 'entered', 'verified');
        }
        return view('reports.district', compact('examination', 'data'));
    }

    public function userPerformance(Examination $examination)
    {
        $users = User::whereHas('marksEntered', fn($q) => $q->where('examination_id', $examination->id))
            ->withCount(['marksEntered as entered_count' => fn($q) => $q->where('examination_id', $examination->id)])
            ->get();
        return view('reports.user-performance', compact('examination', 'users'));
    }
}
