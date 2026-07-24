<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Notification;
use App\Traits\Auditable;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    use Auditable;

    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(25);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = ['super_admin' => 'Super Administrator', 'exam_admin' => 'Examination Administrator', 'moderator' => 'Moderator', 'marker' => 'Marker', 'data_entry' => 'Data Entry', 'viewer' => 'Viewer'];
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:super_admin,exam_admin,moderator,marker,data_entry,viewer',
            'phone' => 'nullable|string|max:20',
        ]);
        $password = Str::random(12);
        $data['password'] = $password;
        $data['force_password_change'] = true;
        $user = User::create($data);
        $this->logAction('create', 'Users', "Created user {$user->name} ({$user->email}) with role {$user->role}");
        return redirect()->route('users.index')->with('status', "User created. Temporary password: {$password}");
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:super_admin,exam_admin,moderator,marker,data_entry,viewer',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);
        $old = $user->toArray();
        $user->update($data);
        $this->logAction('update', 'Users', "Updated user {$user->name}", $old, $user->toArray());
        return redirect()->route('users.index')->with('status', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }
        $user->delete();
        $this->logAction('delete', 'Users', "Deleted user {$user->name}");
        return redirect()->route('users.index')->with('status', 'User deleted.');
    }

    public function resetPassword(User $user)
    {
        $password = Str::random(12);
        $user->update(['password' => $password, 'force_password_change' => true]);
        $this->logAction('reset_password', 'Users', "Reset password for {$user->name}");
        Notification::create([
            'user_id' => $user->id,
            'title' => 'Password Reset',
            'message' => "Your password has been reset. New password: {$password}",
            'type' => 'warning',
        ]);
        return back()->with('status', "Password reset. New password: {$password}");
    }

    public function forcePasswordChange(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);
        $user = auth()->user();
        $user->update(['password' => $request->password, 'force_password_change' => false]);
        $this->logAction('password_change', 'Users', "Changed password");
        return redirect()->route('home')->with('status', 'Password changed successfully.');
    }
}
