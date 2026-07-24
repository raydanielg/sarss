@extends('layouts.dashboard')
@section('title', 'Users - ' . config('app.name'))
@section('page_title', 'User Management')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <p class="text-sm text-gray-500">Manage user accounts and roles.</p>
    <a href="{{ route('users.create') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg transition-colors inline-flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add User
    </a>
</div>

<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead><tr class="text-left text-xs text-gray-500 bg-gray-50/50 border-b">
            <th class="px-5 py-3 font-medium">Name</th>
            <th class="px-5 py-3 font-medium">Email</th>
            <th class="px-5 py-3 font-medium">Role</th>
            <th class="px-5 py-3 font-medium">Phone</th>
            <th class="px-5 py-3 font-medium">Status</th>
            <th class="px-5 py-3 font-medium text-right">Actions</th>
        </tr></thead>
        <tbody>
            @forelse($users as $user)
            @php $roleLabels = ['super_admin'=>'Super Admin','exam_admin'=>'Exam Admin','moderator'=>'Moderator','marker'=>'Marker','data_entry'=>'Data Entry','viewer'=>'Viewer']; @endphp
            <tr class="border-b border-gray-50 hover:bg-gray-50/50">
                <td class="px-5 py-3 font-semibold text-gray-900">{{ $user->name }}</td>
                <td class="px-5 py-3 text-gray-600 text-xs">{{ $user->email }}</td>
                <td class="px-5 py-3"><span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">{{ $roleLabels[$user->role] ?? $user->role }}</span></td>
                <td class="px-5 py-3 text-gray-600 text-xs">{{ $user->phone ?? '—' }}</td>
                <td class="px-5 py-3">@if($user->is_active)<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-50 text-emerald-700">Active</span>@else<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-gray-100 text-gray-500">Inactive</span>@endif</td>
                <td class="px-5 py-3 text-right">
                    <button onclick="editUser({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', '{{ $user->role }}', '{{ $user->phone ?? '' }}', {{ $user->is_active ? 1 : 0 }})" class="text-emerald-600 hover:text-emerald-700 text-xs font-medium">Edit</button>
                    <form action="{{ route('users.reset-password', $user) }}" method="POST" class="inline" onsubmit="return confirm('Reset password for this user?')">@csrf<button class="text-amber-600 hover:text-amber-700 text-xs font-medium ml-3">Reset Pwd</button></form>
                    @if($user->id !== auth()->id())<form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Delete this user?')">@csrf @method('DELETE')<button class="text-red-500 hover:text-red-600 text-xs font-medium ml-3">Delete</button></form>@endif
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-5 py-8 text-center text-gray-400">No users found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $users->links() }}

<div id="modal-edit" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Edit User</h3>
        <form id="form-edit" method="POST">
            @csrf @method('PUT')
            <div class="space-y-4">
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">Name</label><input type="text" name="name" id="edit-name" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm outline-none"></div>
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">Email</label><input type="email" name="email" id="edit-email" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm outline-none"></div>
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">Role</label><select name="role" id="edit-role" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm outline-none">@foreach(['super_admin'=>'Super Administrator','exam_admin'=>'Examination Administrator','moderator'=>'Moderator','marker'=>'Marker','data_entry'=>'Data Entry','viewer'=>'Viewer'] as $val=>$label)<option value="{{ $val }}">{{ $label }}</option>@endforeach</select></div>
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">Phone</label><input type="text" name="phone" id="edit-phone" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm outline-none"></div>
                <div class="flex items-center gap-2"><input type="checkbox" name="is_active" id="edit-active" value="1" class="rounded border-gray-300 text-emerald-600"><label class="text-xs font-semibold text-gray-600">Active</label></div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="document.getElementById('modal-edit').classList.toggle('hidden')" class="flex-1 px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-600">Cancel</button>
                <button type="submit" class="flex-1 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-semibold">Update</button>
            </div>
        </form>
    </div>
</div>
<script>
function editUser(id, name, email, role, phone, active) {
    document.getElementById('form-edit').action = '{{ route("users.update", "__ID__") }}'.replace('__ID__', id);
    document.getElementById('edit-name').value = name;
    document.getElementById('edit-email').value = email;
    document.getElementById('edit-role').value = role;
    document.getElementById('edit-phone').value = phone;
    document.getElementById('edit-active').checked = active === 1;
    document.getElementById('modal-edit').classList.toggle('hidden');
}
</script>
@endsection
