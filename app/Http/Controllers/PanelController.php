<?php

namespace App\Http\Controllers;

use App\Models\Panel;
use App\Models\Examination;
use App\Models\Subject;
use App\Models\User;
use App\Models\PanelMarker;
use App\Models\PanelDataEntry;
use App\Models\Notification;
use App\Traits\Auditable;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PanelController extends Controller
{
    use Auditable;

    public function index()
    {
        $panels = Panel::with(['examination', 'subject', 'moderator'])
            ->withCount(['markers', 'dataEntries', 'assignments'])
            ->orderBy('created_at', 'desc')->get();
        return view('panels.index', compact('panels'));
    }

    public function create()
    {
        $examinations = Examination::where('status', 'open')->orderBy('created_at', 'desc')->get();
        $subjects = Subject::orderBy('name')->get();
        $moderators = User::where('role', 'moderator')->orWhere('role', 'exam_admin')->orderBy('name')->get();
        return view('panels.create', compact('examinations', 'subjects', 'moderators'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'examination_id' => 'required|exists:examinations,id',
            'subject_id' => 'required|exists:subjects,id',
            'moderator_user_id' => 'required|exists:users,id',
        ]);
        $panel = Panel::create($data);
        $this->logAction('create', 'Panels', "Created panel for {$panel->subject->name} in {$panel->examination->name}");
        return redirect()->route('panels.index')->with('status', 'Panel created successfully.');
    }

    public function show(Panel $panel)
    {
        $panel->load(['examination', 'subject', 'moderator', 'markers.school', 'dataEntries.user', 'assignments.district', 'assignments.schools', 'assignments.user']);
        $schools = \App\Models\School::orderBy('name')->get();
        return view('panels.show', compact('panel', 'schools'));
    }

    public function destroy(Panel $panel)
    {
        $panel->delete();
        $this->logAction('delete', 'Panels', "Deleted panel for {$panel->subject->name}");
        return redirect()->route('panels.index')->with('status', 'Panel deleted.');
    }

    public function addMarker(Request $request, Panel $panel)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'school_id' => 'nullable|exists:schools,id',
        ]);
        $marker = PanelMarker::create(array_merge($data, ['panel_id' => $panel->id]));
        $this->logAction('create', 'Panel Markers', "Added marker {$marker->name} to panel");
        return back()->with('status', 'Marker added successfully.');
    }

    public function removeMarker(Panel $panel, PanelMarker $marker)
    {
        $marker->delete();
        $this->logAction('delete', 'Panel Markers', "Removed marker {$marker->name}");
        return back()->with('status', 'Marker removed.');
    }

    public function addDataEntry(Request $request, Panel $panel)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email|unique:users,email',
        ]);
        $username = Str::slug(explode(' ', $data['name'])[0]) . rand(100, 999);
        $password = Str::random(10);
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $password,
            'role' => 'data_entry',
            'phone' => $data['phone'] ?? null,
            'force_password_change' => true,
        ]);
        PanelDataEntry::create(['panel_id' => $panel->id, 'user_id' => $user->id]);
        Notification::create([
            'user_id' => $user->id,
            'title' => 'Welcome to e-Mark',
            'message' => "You have been assigned as a Data Entry officer for {$panel->subject->name}. Your temporary password is: {$password}",
            'type' => 'info',
        ]);
        $this->logAction('create', 'Panel Data Entry', "Added data entry officer {$user->name} to panel. Username: {$user->email}");
        return back()->with('status', "Data Entry officer added. Username: {$user->email}, Password: {$password}");
    }

    public function removeDataEntry(Panel $panel, PanelDataEntry $dataEntry)
    {
        $dataEntry->delete();
        $this->logAction('delete', 'Panel Data Entry', "Removed data entry officer");
        return back()->with('status', 'Data Entry officer removed.');
    }
}
