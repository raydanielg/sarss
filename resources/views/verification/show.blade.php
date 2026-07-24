@extends('layouts.dashboard')
@section('title', 'Verification - ' . config('app.name'))
@section('page_title', $panel->subject->name . ' Verification')

@section('content')
<div class="mb-6"><a href="{{ route('verification.index') }}" class="text-xs text-gray-400 hover:text-gray-600">&larr; Back to Panels</a></div>

<div class="grid grid-cols-2 lg:grid-cols-5 gap-3 mb-6">
    @foreach([['Total',$stats['total'],'gray'],['Pending',$stats['pending'],'gray'],['Entered',$stats['entered'],'sky'],['Verified',$stats['verified'],'emerald'],['Rejected',$stats['rejected'],'red']] as $s)
    <div class="bg-{{ $s[2] }}-50 rounded-xl border border-{{ $s[2] }}-100 p-3 text-center">
        <p class="text-2xl font-bold text-{{ $s[2] }}-700">{{ $s[1] }}</p>
        <p class="text-[10px] text-{{ $s[2] }}-500 font-medium">{{ $s[0] }}</p>
    </div>
    @endforeach
</div>

<div class="mb-4 flex justify-end">
    <form action="{{ route('verification.bulk-approve', $panel) }}" method="POST" onsubmit="return confirm('Approve all entered marks?')">@csrf<button class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold">Bulk Approve Entered</button></form>
</div>

<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead><tr class="text-left text-xs text-gray-500 bg-gray-50/50 border-b">
            <th class="px-5 py-3 font-medium">Candidate No.</th>
            <th class="px-5 py-3 font-medium">Name</th>
            <th class="px-5 py-3 font-medium">School</th>
            <th class="px-5 py-3 font-medium">Mark</th>
            <th class="px-5 py-3 font-medium">Status</th>
            <th class="px-5 py-3 font-medium text-right">Actions</th>
        </tr></thead>
        <tbody>
            @forelse($marks as $mark)
            <tr class="border-b border-gray-50 hover:bg-gray-50/50">
                <td class="px-5 py-2.5 font-mono text-xs text-gray-700">{{ $mark->candidate->candidate_number ?? '—' }}</td>
                <td class="px-5 py-2.5 font-semibold text-gray-900">{{ $mark->candidate->name ?? '—' }}</td>
                <td class="px-5 py-2.5 text-gray-600 text-xs">{{ $mark->school->name ?? '—' }}</td>
                <td class="px-5 py-2.5 font-bold text-gray-900">{{ $mark->mark ?? '—' }}</td>
                <td class="px-5 py-2.5">
                    @if($mark->status === 'verified')<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">Verified</span>
                    @elseif($mark->status === 'entered')<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-sky-50 text-sky-700 border border-sky-100">Entered</span>
                    @elseif($mark->status === 'rejected')<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-red-50 text-red-700 border border-red-100" title="{{ $mark->rejection_reason }}">Rejected</span>
                    @else<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-gray-50 text-gray-400">Pending</span>@endif
                </td>
                <td class="px-5 py-2.5 text-right">
                    @if($mark->status === 'entered')
                    <form action="{{ route('verification.approve', $mark) }}" method="POST" class="inline">@csrf<button class="text-emerald-600 hover:text-emerald-700 text-xs font-medium">Approve</button></form>
                    <button onclick="rejectMark({{ $mark->id }})" class="text-red-500 hover:text-red-600 text-xs font-medium ml-3">Reject</button>
                    @elseif($mark->status === 'rejected')
                    <form action="{{ route('verification.approve', $mark) }}" method="POST" class="inline">@csrf<button class="text-emerald-600 hover:text-emerald-700 text-xs font-medium">Approve</button></form>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-5 py-8 text-center text-gray-400">No marks yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $marks->links() }}

<div id="modal-reject" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Reject Marks</h3>
        <form id="form-reject" method="POST">
            @csrf
            <div><label class="block text-xs font-semibold text-gray-600 mb-1">Rejection Reason</label><textarea name="rejection_reason" required rows="3" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm outline-none"></textarea></div>
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="document.getElementById('modal-reject').classList.toggle('hidden')" class="flex-1 px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-600">Cancel</button>
                <button type="submit" class="flex-1 px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg text-sm font-semibold">Reject</button>
            </div>
        </form>
    </div>
</div>
<script>
function rejectMark(id) {
    document.getElementById('form-reject').action = '{{ route("verification.reject", "__ID__") }}'.replace('__ID__', id);
    document.getElementById('modal-reject').classList.toggle('hidden');
}
</script>
@endsection
