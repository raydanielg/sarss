<?php

namespace App\Http\Controllers;

use App\Models\ExamType;
use App\Traits\Auditable;
use Illuminate\Http\Request;

class ExamTypeController extends Controller
{
    use Auditable;

    public function index()
    {
        $types = ExamType::orderBy('name')->get();
        return view('setup.exam-types.index', compact('types'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:exam_types,code',
            'description' => 'nullable|string',
        ]);
        $type = ExamType::create($data);
        $this->logAction('create', 'Exam Types', "Created exam type {$type->name}");
        return redirect()->route('exam-types.index')->with('status', 'Exam type created successfully.');
    }

    public function update(Request $request, ExamType $examType)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:exam_types,code,' . $examType->id,
            'description' => 'nullable|string',
        ]);
        $old = $examType->toArray();
        $examType->update($data);
        $this->logAction('update', 'Exam Types', "Updated exam type {$examType->name}", $old, $examType->toArray());
        return redirect()->route('exam-types.index')->with('status', 'Exam type updated successfully.');
    }

    public function destroy(ExamType $examType)
    {
        $examType->delete();
        $this->logAction('delete', 'Exam Types', "Deleted exam type {$examType->name}");
        return redirect()->route('exam-types.index')->with('status', 'Exam type deleted.');
    }
}
