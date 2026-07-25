@extends('layouts.dashboard')
@section('title', 'Districts - ' . config('app.name'))
@section('page_title', 'Districts')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <p class="text-sm text-gray-500">Manage districts within regions.</p>
    <button onclick="openModal('modal-create')" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg transition-colors inline-flex items-center gap-2 shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add District
    </button>
</div>

<div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead><tr class="text-left text-xs text-gray-500 bg-gray-50/50 border-b">
            <th class="px-5 py-3 font-medium">Name</th>
            <th class="px-5 py-3 font-medium">Code</th>
            <th class="px-5 py-3 font-medium">Region</th>
            <th class="px-5 py-3 font-medium">Schools</th>
            <th class="px-5 py-3 font-medium text-right">Actions</th>
        </tr></thead>
        <tbody>
            @forelse($districts as $district)
            <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors">
                <td class="px-5 py-3 font-semibold text-gray-900">{{ $district->name }}</td>
                <td class="px-5 py-3 text-gray-600 font-mono text-xs">{{ $district->code }}</td>
                <td class="px-5 py-3 text-gray-600">{{ $district->region->name ?? '—' }}</td>
                <td class="px-5 py-3 text-gray-600">{{ $district->schools_count }}</td>
                <td class="px-5 py-3 text-right">
                    <div class="flex items-center justify-end gap-1">
                        <button onclick="editDistrict({{ $district->id }}, '{{ $district->name }}', '{{ $district->code }}', {{ $district->region_id }})" class="w-8 h-8 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-600 flex items-center justify-center transition-colors" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <button onclick="deleteItem('{{ route("districts.destroy", $district) }}')" class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 flex items-center justify-center transition-colors" title="Delete">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-5 py-12 text-center">
                <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-4a1 1 0 011-1h2a1 1 0 011 1v4"/></svg>
                <p class="text-sm text-gray-400">No districts yet.</p>
                <button onclick="openModal('modal-create')" class="mt-3 text-xs font-medium text-emerald-600 hover:text-emerald-700">Add your first district</button>
            </td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div id="modal-create" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900">Add District</h3>
            <button type="button" onclick="closeModal('modal-create')" class="w-8 h-8 rounded-lg hover:bg-gray-100 flex items-center justify-center text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="{{ route('districts.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">Region</label>
                    <select name="region_id" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none">
                        @foreach($regions as $region)<option value="{{ $region->id }}">{{ $region->name }}</option>@endforeach
                    </select>
                </div>
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">Name</label><input type="text" name="name" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none" placeholder="Ilemela"></div>
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">Code</label><input type="text" name="code" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none" placeholder="ILM"></div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="closeModal('modal-create')" class="flex-1 px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50">Cancel</button>
                <button type="submit" class="flex-1 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-semibold shadow-sm">Create</button>
            </div>
        </form>
    </div>
</div>

<div id="modal-edit" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900">Edit District</h3>
            <button type="button" onclick="closeModal('modal-edit')" class="w-8 h-8 rounded-lg hover:bg-gray-100 flex items-center justify-center text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="form-edit" method="POST">
            @csrf @method('PUT')
            <div class="space-y-4">
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">Region</label>
                    <select name="region_id" id="edit-region-id" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none">
                        @foreach($regions as $region)<option value="{{ $region->id }}">{{ $region->name }}</option>@endforeach
                    </select>
                </div>
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">Name</label><input type="text" name="name" id="edit-name" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none"></div>
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">Code</label><input type="text" name="code" id="edit-code" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none"></div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="closeModal('modal-edit')" class="flex-1 px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50">Cancel</button>
                <button type="submit" class="flex-1 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-semibold shadow-sm">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
function closeModal(id) { document.getElementById(id).classList.add('hidden'); }
function editDistrict(id, name, code, regionId) {
    document.getElementById('form-edit').action = '{{ route("districts.update", "__ID__") }}'.replace('__ID__', id);
    document.getElementById('edit-name').value = name;
    document.getElementById('edit-code').value = code;
    document.getElementById('edit-region-id').value = regionId;
    openModal('modal-edit');
}
function deleteItem(url) {
    Swal.fire({
        title: 'Delete this item?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        customClass: { popup: 'rounded-xl', confirmButton: 'px-5 py-2 rounded-lg text-sm font-semibold text-white', cancelButton: 'px-5 py-2 rounded-lg text-sm font-semibold text-white' },
        buttonsStyling: false,
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
