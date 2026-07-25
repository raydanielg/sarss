@extends('layouts.dashboard')
@section('title', 'Assignments - ' . config('app.name'))
@section('page_title', 'Assignments')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <p class="text-sm text-gray-500">Manage work assignments for data entry officers.</p>
</div>

<div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead><tr class="text-left text-xs text-gray-500 bg-gray-50/50 border-b">
            <th class="px-5 py-3 font-medium">Officer</th>
            <th class="px-5 py-3 font-medium">Subject</th>
            <th class="px-5 py-3 font-medium">District</th>
            <th class="px-5 py-3 font-medium">Schools</th>
            <th class="px-5 py-3 font-medium">Examination</th>
            <th class="px-5 py-3 font-medium text-right">Actions</th>
        </tr></thead>
        <tbody>
            @forelse($assignments as $assignment)
            <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors">
                <td class="px-5 py-3 font-semibold text-gray-900">{{ $assignment->user->name ?? '—' }}</td>
                <td class="px-5 py-3 text-gray-600">{{ $assignment->panel->subject->name ?? '—' }}</td>
                <td class="px-5 py-3 text-gray-600">{{ $assignment->district->name ?? '—' }}</td>
                <td class="px-5 py-3 text-gray-600 text-xs">{{ $assignment->schools->pluck('name')->implode(', ') }}</td>
                <td class="px-5 py-3 text-gray-600 text-xs">{{ $assignment->panel->examination->name ?? '—' }}</td>
                <td class="px-5 py-3 text-right">
                    <button onclick="deleteAssignment('{{ route("assignments.destroy", $assignment) }}')" class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 flex items-center justify-center transition-colors" title="Delete">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-5 py-12 text-center">
                <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <p class="text-sm text-gray-400">No assignments yet.</p>
            </td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
function deleteAssignment(url) {
    Swal.fire({
        title: 'Delete this assignment?',
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
