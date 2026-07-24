@extends('layouts.dashboard')
@section('title', 'Subjects - ' . config('app.name'))
@section('page_title', 'Subjects')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <p class="text-sm text-gray-500">Manage examinable subjects.</p>
    <button onclick="document.getElementById('modal-create').classList.toggle('hidden')" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg transition-colors inline-flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Subject
    </button>
</div>

<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead><tr class="text-left text-xs text-gray-500 bg-gray-50/50 border-b">
            <th class="px-5 py-3 font-medium">Name</th>
            <th class="px-5 py-3 font-medium">Code</th>
            <th class="px-5 py-3 font-medium">Max Marks</th>
            <th class="px-5 py-3 font-medium text-right">Actions</th>
        </tr></thead>
        <tbody>
            @forelse($subjects as $subject)
            <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors">
                <td class="px-5 py-3 font-semibold text-gray-900">{{ $subject->name }}</td>
                <td class="px-5 py-3 text-gray-600 font-mono text-xs">{{ $subject->code }}</td>
                <td class="px-5 py-3 text-gray-600">{{ $subject->max_marks }}</td>
                <td class="px-5 py-3 text-right">
                    <button onclick="editSubject({{ $subject->id }}, '{{ $subject->name }}', '{{ $subject->code }}', {{ $subject->max_marks }})" class="text-emerald-600 hover:text-emerald-700 text-xs font-medium">Edit</button>
                    <form action="{{ route('subjects.destroy', $subject) }}" method="POST" class="inline" onsubmit="return confirm('Delete this subject?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-600 text-xs font-medium ml-3">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-5 py-8 text-center text-gray-400">No subjects yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div id="modal-create" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Add Subject</h3>
        <form action="{{ route('subjects.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">Name</label><input type="text" name="name" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none" placeholder="Mathematics"></div>
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">Code</label><input type="text" name="code" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none" placeholder="MATH"></div>
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">Max Marks</label><input type="number" name="max_marks" required value="100" min="1" max="1000" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none"></div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="document.getElementById('modal-create').classList.toggle('hidden')" class="flex-1 px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50">Cancel</button>
                <button type="submit" class="flex-1 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-semibold">Create</button>
            </div>
        </form>
    </div>
</div>

<div id="modal-edit" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Edit Subject</h3>
        <form id="form-edit" method="POST">
            @csrf @method('PUT')
            <div class="space-y-4">
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">Name</label><input type="text" name="name" id="edit-name" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none"></div>
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">Code</label><input type="text" name="code" id="edit-code" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none"></div>
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">Max Marks</label><input type="number" name="max_marks" id="edit-max-marks" required min="1" max="1000" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none"></div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="document.getElementById('modal-edit').classList.toggle('hidden')" class="flex-1 px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50">Cancel</button>
                <button type="submit" class="flex-1 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-semibold">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
function editSubject(id, name, code, maxMarks) {
    document.getElementById('form-edit').action = '{{ route("subjects.update", "__ID__") }}'.replace('__ID__', id);
    document.getElementById('edit-name').value = name;
    document.getElementById('edit-code').value = code;
    document.getElementById('edit-max-marks').value = maxMarks;
    document.getElementById('modal-edit').classList.toggle('hidden');
}
</script>
@endsection
