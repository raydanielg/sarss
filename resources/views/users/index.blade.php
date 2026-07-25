@extends('layouts.dashboard')
@section('title', 'Users - ' . config('app.name'))
@section('page_title', 'User Management')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <p class="text-sm text-gray-500">Manage user accounts and roles.</p>
    <a href="{{ route('users.create') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg transition-colors inline-flex items-center gap-2 shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add User
    </a>
</div>

@php
    $roleStyles = [
        'super_admin' => ['label'=>'Super Admin','bg'=>'bg-violet-50','text'=>'text-violet-700','border'=>'border-violet-100','avatar'=>'bg-violet-500'],
        'exam_admin'  => ['label'=>'Exam Admin','bg'=>'bg-sky-50','text'=>'text-sky-700','border'=>'border-sky-100','avatar'=>'bg-sky-500'],
        'moderator'   => ['label'=>'Moderator','bg'=>'bg-amber-50','text'=>'text-amber-700','border'=>'border-amber-100','avatar'=>'bg-amber-500'],
        'marker'      => ['label'=>'Marker','bg'=>'bg-emerald-50','text'=>'text-emerald-700','border'=>'border-emerald-100','avatar'=>'bg-emerald-500'],
        'data_entry'  => ['label'=>'Data Entry','bg'=>'bg-teal-50','text'=>'text-teal-700','border'=>'border-teal-100','avatar'=>'bg-teal-500'],
        'viewer'      => ['label'=>'Viewer','bg'=>'bg-gray-100','text'=>'text-gray-600','border'=>'border-gray-200','avatar'=>'bg-gray-500'],
    ];
@endphp

<div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
    <table class="w-full text-sm">
        <thead><tr class="text-left text-xs text-gray-500 bg-gray-50/50 border-b">
            <th class="px-5 py-3 font-medium">User</th>
            <th class="px-5 py-3 font-medium">Role</th>
            <th class="px-5 py-3 font-medium">Phone</th>
            <th class="px-5 py-3 font-medium">Status</th>
            <th class="px-5 py-3 font-medium text-right">Actions</th>
        </tr></thead>
        <tbody>
            @forelse($users as $user)
            @php $rs = $roleStyles[$user->role] ?? $roleStyles['viewer']; @endphp
            <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors">
                <td class="px-5 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full {{ $rs['avatar'] }} flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">{{ $user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $user->email }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-3"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold {{ $rs['bg'] }} {{ $rs['text'] }} border {{ $rs['border'] }}">{{ $rs['label'] }}</span></td>
                <td class="px-5 py-3 text-gray-600 text-xs">{{ $user->phone ?? '—' }}</td>
                <td class="px-5 py-3">@if($user->is_active)<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Active</span>@else<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-gray-100 text-gray-500 border border-gray-200"><span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>Inactive</span>@endif</td>
                <td class="px-5 py-3 text-right">
                    <div class="flex items-center justify-end gap-1.5">
                        <button onclick="editUser({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', '{{ $user->role }}', '{{ $user->phone ?? '' }}', {{ $user->is_active ? 1 : 0 }})" class="w-8 h-8 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-600 flex items-center justify-center transition-colors" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <button onclick="resetPassword('{{ route('users.reset-password', $user) }}', '{{ $user->name }}')" class="w-8 h-8 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-600 flex items-center justify-center transition-colors" title="Reset Password">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                        </button>
                        @if($user->id !== auth()->id())
                        <button onclick="deleteUser('{{ route('users.destroy', $user) }}', '{{ $user->name }}')" class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 flex items-center justify-center transition-colors" title="Delete">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-5 py-12 text-center">
                <svg class="w-14 h-14 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <p class="text-sm text-gray-400 mb-3">No users found.</p>
                <a href="{{ route('users.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add User
                </a>
            </td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $users->links() }}</div>

<div id="modal-edit" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900">Edit User</h3>
            <button type="button" onclick="document.getElementById('modal-edit').classList.add('hidden')" class="w-8 h-8 rounded-lg hover:bg-gray-100 flex items-center justify-center text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="form-edit" method="POST">
            @csrf @method('PUT')
            <div class="space-y-4">
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">Name</label><input type="text" name="name" id="edit-name" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none"></div>
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">Email</label><input type="email" name="email" id="edit-email" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none"></div>
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">Role</label><select name="role" id="edit-role" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none">@foreach(['super_admin'=>'Super Administrator','exam_admin'=>'Examination Administrator','moderator'=>'Moderator','marker'=>'Marker','data_entry'=>'Data Entry','viewer'=>'Viewer'] as $val=>$label)<option value="{{ $val }}">{{ $label }}</option>@endforeach</select></div>
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">Phone</label><input type="text" name="phone" id="edit-phone" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none"></div>
                <div class="flex items-center gap-2"><input type="checkbox" name="is_active" id="edit-active" value="1" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-100"><label class="text-xs font-semibold text-gray-600">Active</label></div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="document.getElementById('modal-edit').classList.add('hidden')" class="flex-1 px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50">Cancel</button>
                <button type="submit" class="flex-1 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-semibold shadow-sm">Update</button>
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
    document.getElementById('modal-edit').classList.remove('hidden');
}

function resetPassword(url, name) {
    Swal.fire({
        title: 'Reset password for ' + name + '?',
        text: 'A new temporary password will be generated.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Reset',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#f59e0b',
        cancelButtonColor: '#6b7280',
        customClass: { popup: 'rounded-xl' },
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            form.innerHTML = '<input type="hidden" name="_token" value="' + document.querySelector('meta[name="csrf-token"]').content + '">';
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function deleteUser(url, name) {
    Swal.fire({
        title: 'Delete ' + name + '?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        customClass: { popup: 'rounded-xl' },
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            form.innerHTML = '<input type="hidden" name="_token" value="' + document.querySelector('meta[name="csrf-token"]').content + '"><input type="hidden" name="_method" value="DELETE">';
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endsection
