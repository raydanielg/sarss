<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\District;
use App\Traits\Auditable;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    use Auditable;

    public function index()
    {
        $schools = School::with('district.region')->withCount('candidates')->orderBy('name')->get();
        $districts = District::with('region')->orderBy('name')->get();
        return view('setup.schools.index', compact('schools', 'districts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'district_id' => 'required|exists:districts,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:schools,code',
            'registration_number' => 'nullable|string',
        ]);
        $school = School::create($data);
        $this->logAction('create', 'Schools', "Created school {$school->name}");
        return redirect()->route('schools.index')->with('status', 'School created successfully.');
    }

    public function update(Request $request, School $school)
    {
        $data = $request->validate([
            'district_id' => 'required|exists:districts,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:schools,code,' . $school->id,
            'registration_number' => 'nullable|string',
        ]);
        $old = $school->toArray();
        $school->update($data);
        $this->logAction('update', 'Schools', "Updated school {$school->name}", $old, $school->toArray());
        return redirect()->route('schools.index')->with('status', 'School updated successfully.');
    }

    public function destroy(School $school)
    {
        $school->delete();
        $this->logAction('delete', 'Schools', "Deleted school {$school->name}");
        return redirect()->route('schools.index')->with('status', 'School deleted.');
    }
}
