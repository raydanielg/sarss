@extends('layouts.dashboard')
@section('title', 'Academic Years - ' . config('app.name'))
@section('page_title', 'Academic Years')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <p class="text-sm text-gray-500">Manage academic years for examinations.</p>
    <button onclick="document.getElementById('modal-create').classList.toggle('hidden')" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg transition-colors inline-flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Year
    </button>
</div>

<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead><tr class="text-left text-xs text-gray-500 bg-gray-50/50 border-b">
            <th class="px-5 py-3 font-medium">Year</th>
            <th class="px-5 py-3 font-medium">Description</th>
            <th class="px-5 py-3 font-medium">Status</th>
            <th class="px-5 py-3 font-medium text-right">Actions</th>
        </tr></thead>
        <tbody>
            @forelse($years as $year)
            <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors">
                <td class="px-5 py-3 font-semibold text-gray-900">{{ $year->year }}</td>
                <td class="px-5 py-3 text-gray-600">{{ $year->description ?? '—' }}</td>
                <td class="px-5 py-3">
                    @if($year->is_active)
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">Active</span>
                    @else
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-gray-50 text-gray-500 border border-gray-100">Inactive</span>
                    @endif
                </td>
                <td class="px-5 py-3 text-right">
                    <button onclick="editYear({{ $year->id }}, {{ $year->year }}, '{{ $year->description ?? '' }}', {{ $year->is_active ? 1 : 0 }})" class="text-emerald-600 hover:text-emerald-700 text-xs font-medium">Edit</button>
                    <form action="{{ route('academic-years.destroy', $year) }}" method="POST" class="inline" onsubmit="return confirm('Delete this year?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-600 text-xs font-medium ml-3">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-5 py-8 text-center text-gray-400">No academic years yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Create Modal --}}
<div id="modal-create" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Add Academic Year</h3>
        <form action="{{ route('academic-years.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">Year</label><input type="number" name="year" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none" placeholder="2026"></div>
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">Description</label><input type="text" name="description" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none" placeholder="Optional"></div>
                <div class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-emerald-600"><label class="text-xs font-semibold text-gray-600">Active</label></div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="document.getElementById('modal-create').classList.toggle('hidden')" class="flex-1 px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50">Cancel</button>
                <button type="submit" class="flex-1 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-semibold">Create</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Modal --}}
<div id="modal-edit" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Edit Academic Year</h3>
        <form id="form-edit" method="POST">
            @csrf @method('PUT')
            <div class="space-y-4">
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">Year</label><input type="number" name="year" id="edit-year" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none"></div>
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">Description</label><input type="text" name="description" id="edit-description" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none"></div>
                <div class="flex items-center gap-2"><input type="checkbox" name="is_active" id="edit-active" value="1" class="rounded border-gray-300 text-emerald-600"><label class="text-xs font-semibold text-gray-600">Active</label></div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="document.getElementById('modal-edit').classList.toggle('hidden')" class="flex-1 px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50">Cancel</button>
                <button type="submit" class="flex-1 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-semibold">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
function editYear(id, year, desc, active) {
    document.getElementById('form-edit').action = '{{ route("academic-years.update", "__ID__") }}'.replace('__ID__', id);
    document.getElementById('edit-year').value = year;
    document.getElementById('edit-description').value = desc;
    document.getElementById('edit-active').checked = active === 1;
    document.getElementById('modal-edit').classList.toggle('hidden');
}
</script>
@endsection
