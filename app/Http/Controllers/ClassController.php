<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Traits\Auditable;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    use Auditable;

    public function index()
    {
        $classes = Classes::orderBy('level')->get();
        return view('setup.classes.index', compact('classes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:classes,code',
            'level' => 'required|integer|min:1|max:10',
        ]);
        $class = Classes::create($data);
        $this->logAction('create', 'Classes', "Created class {$class->name}");
        return redirect()->route('classes.index')->with('status', 'Class created successfully.');
    }

    public function update(Request $request, Classes $class)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:classes,code,' . $class->id,
            'level' => 'required|integer|min:1|max:10',
        ]);
        $old = $class->toArray();
        $class->update($data);
        $this->logAction('update', 'Classes', "Updated class {$class->name}", $old, $class->toArray());
        return redirect()->route('classes.index')->with('status', 'Class updated successfully.');
    }

    public function destroy(Classes $class)
    {
        $class->delete();
        $this->logAction('delete', 'Classes', "Deleted class {$class->name}");
        return redirect()->route('classes.index')->with('status', 'Class deleted.');
    }
}
