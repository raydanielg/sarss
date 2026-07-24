<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Examination;
use App\Models\School;
use App\Models\District;
use App\Models\Classes;
use App\Models\Stream;
use App\Traits\Auditable;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    use Auditable;

    public function index(Request $request)
    {
        $query = Candidate::with(['school.district', 'class', 'stream', 'examination']);
        if ($request->examination_id) {
            $query->where('examination_id', $request->examination_id);
        }
        if ($request->school_id) {
            $query->where('school_id', $request->school_id);
        }
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('candidate_number', 'like', "%{$request->search}%");
            });
        }
        $candidates = $query->orderBy('candidate_number')->paginate(50);
        $examinations = Examination::orderBy('created_at', 'desc')->get();
        $schools = School::orderBy('name')->get();
        return view('candidates.index', compact('candidates', 'examinations', 'schools'));
    }

    public function create()
    {
        $examinations = Examination::where('status', '!=', 'archived')->orderBy('created_at', 'desc')->get();
        $schools = School::with('district')->orderBy('name')->get();
        $districts = District::orderBy('name')->get();
        $classes = Classes::orderBy('level')->get();
        $streams = Stream::orderBy('name')->get();
        return view('candidates.create', compact('examinations', 'schools', 'districts', 'classes', 'streams'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'examination_id' => 'required|exists:examinations,id',
            'candidate_number' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'gender' => 'nullable|in:male,female',
            'school_id' => 'required|exists:schools,id',
            'district_id' => 'required|exists:districts,id',
            'class_id' => 'required|exists:classes,id',
            'stream_id' => 'nullable|exists:streams,id',
        ]);
        $candidate = Candidate::create($data);
        $this->logAction('create', 'Candidates', "Created candidate {$candidate->name} ({$candidate->candidate_number})");
        return redirect()->route('candidates.index')->with('status', 'Candidate added successfully.');
    }

    public function update(Request $request, Candidate $candidate)
    {
        $data = $request->validate([
            'examination_id' => 'required|exists:examinations,id',
            'candidate_number' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'gender' => 'nullable|in:male,female',
            'school_id' => 'required|exists:schools,id',
            'district_id' => 'required|exists:districts,id',
            'class_id' => 'required|exists:classes,id',
            'stream_id' => 'nullable|exists:streams,id',
        ]);
        $old = $candidate->toArray();
        $candidate->update($data);
        $this->logAction('update', 'Candidates', "Updated candidate {$candidate->name}", $old, $candidate->toArray());
        return redirect()->route('candidates.index')->with('status', 'Candidate updated successfully.');
    }

    public function destroy(Candidate $candidate)
    {
        $candidate->delete();
        $this->logAction('delete', 'Candidates', "Deleted candidate {$candidate->name}");
        return redirect()->route('candidates.index')->with('status', 'Candidate deleted.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'examination_id' => 'required|exists:examinations,id',
            'file' => 'required|file|mimes:csv,txt,xlsx,xls',
        ]);
        // CSV import logic
        $file = $request->file('file');
        $path = $file->getRealPath();
        $handle = fopen($path, 'r');
        $header = fgetcsv($handle);
        $count = 0;
        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 5) continue;
            $school = School::where('code', $row[3] ?? '')->first();
            $district = District::where('code', $row[4] ?? '')->first();
            $class = Classes::where('code', $row[5] ?? '')->first();
            if (!$school || !$district || !$class) continue;
            Candidate::create([
                'examination_id' => $request->examination_id,
                'candidate_number' => $row[0],
                'name' => $row[1],
                'gender' => ($row[2] ?? '') === 'M' ? 'male' : (($row[2] ?? '') === 'F' ? 'female' : null),
                'school_id' => $school->id,
                'district_id' => $district->id,
                'class_id' => $class->id,
            ]);
            $count++;
        }
        fclose($handle);
        $this->logAction('import', 'Candidates', "Imported {$count} candidates");
        return redirect()->route('candidates.index')->with('status', "{$count} candidates imported successfully.");
    }
}
