<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Traits\Auditable;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    use Auditable;

    public function index()
    {
        $years = AcademicYear::orderBy('year', 'desc')->get();
        return view('setup.academic-years.index', compact('years'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'year' => 'required|integer|unique:academic_years,year',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        $year = AcademicYear::create($data);
        $this->logAction('create', 'Academic Years', "Created academic year {$year->year}");
        return redirect()->route('academic-years.index')->with('status', 'Academic year created successfully.');
    }

    public function update(Request $request, AcademicYear $academicYear)
    {
        $data = $request->validate([
            'year' => 'required|integer|unique:academic_years,year,' . $academicYear->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        $old = $academicYear->toArray();
        $academicYear->update($data);
        $this->logAction('update', 'Academic Years', "Updated academic year {$academicYear->year}", $old, $academicYear->toArray());
        return redirect()->route('academic-years.index')->with('status', 'Academic year updated successfully.');
    }

    public function destroy(AcademicYear $academicYear)
    {
        $academicYear->delete();
        $this->logAction('delete', 'Academic Years', "Deleted academic year {$academicYear->year}");
        return redirect()->route('academic-years.index')->with('status', 'Academic year deleted.');
    }
}
