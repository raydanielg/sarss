<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Traits\Auditable;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    use Auditable;

    public function index()
    {
        $subjects = Subject::orderBy('name')->get();
        return view('setup.subjects.index', compact('subjects'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:subjects,code',
            'max_marks' => 'required|integer|min:1|max:1000',
        ]);
        $subject = Subject::create($data);
        $this->logAction('create', 'Subjects', "Created subject {$subject->name}");
        return redirect()->route('subjects.index')->with('status', 'Subject created successfully.');
    }

    public function update(Request $request, Subject $subject)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:subjects,code,' . $subject->id,
            'max_marks' => 'required|integer|min:1|max:1000',
        ]);
        $old = $subject->toArray();
        $subject->update($data);
        $this->logAction('update', 'Subjects', "Updated subject {$subject->name}", $old, $subject->toArray());
        return redirect()->route('subjects.index')->with('status', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        $this->logAction('delete', 'Subjects', "Deleted subject {$subject->name}");
        return redirect()->route('subjects.index')->with('status', 'Subject deleted.');
    }
}
