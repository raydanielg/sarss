@extends('layouts.dashboard')
@section('title', $panel->subject->name . ' Panel - ' . config('app.name'))
@section('page_title', $panel->subject->name . ' Panel')

@section('content')
<div class="mb-6 flex items-center justify-between no-print">
    <a href="{{ route('panels.index') }}" class="text-xs text-gray-400 hover:text-gray-600 inline-flex items-center gap-1">&larr; Back to Panels</a>
    <div class="flex gap-2">
        <button onclick="window.print()" class="px-4 py-2 bg-white border border-gray-200 text-gray-600 rounded-lg text-xs font-semibold hover:bg-gray-50 transition-colors inline-flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Print / PDF
        </button>
        <form action="{{ route('panels.destroy', $panel) }}" method="POST" class="inline">@csrf @method('DELETE')<button onclick="deletePanel('{{ route("panels.destroy", $panel) }}')" type="button" class="px-4 py-2 bg-white border border-red-200 text-red-600 rounded-lg text-xs font-semibold hover:bg-red-50 transition-colors inline-flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            Delete
        </button></form>
    </div>
</div>

{{-- Header Card --}}
<div class="bg-gradient-to-br from-violet-500 to-violet-700 rounded-2xl border border-violet-400 p-6 text-white relative overflow-hidden mb-6">
    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
    <div class="absolute bottom-0 left-0 w-20 h-20 bg-white/5 rounded-full -ml-10 -mb-10"></div>
    <div class="relative z-10 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-white/15 flex items-center justify-center flex-shrink-0">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-white tracking-tight">{{ $panel->subject->name ?? '—' }} Panel</h2>
                <p class="text-xs text-violet-100 mt-1">{{ $panel->examination->name ?? '—' }}</p>
                <p class="text-xs text-violet-200 mt-0.5">Moderator: <span class="font-semibold text-white">{{ $panel->moderator->name ?? '—' }}</span></p>
            </div>
        </div>
    </div>
</div>

{{-- KPI Cards --}}
<div class="grid grid-cols-3 gap-3 mb-6">
    @php
    $kpis = [
        ['label'=>'Markers','value'=>$panel->markers->count(),'sub'=>'Assigned','icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z','from'=>'emerald-500','to'=>'emerald-600','border'=>'emerald-400','text'=>'emerald-100','subtext'=>'emerald-200'],
        ['label'=>'Data Entry','value'=>$panel->dataEntries->count(),'sub'=>'Officers','icon'=>'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z','from'=>'sky-500','to'=>'sky-600','border'=>'sky-400','text'=>'sky-100','subtext'=>'sky-200'],
        ['label'=>'Assignments','value'=>$panel->assignments->count(),'sub'=>'Active','icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2','from'=>'amber-400','to'=>'amber-500','border'=>'amber-300','text'=>'amber-50','subtext'=>'amber-100'],
    ];
    @endphp
    @foreach($kpis as $kpi)
    <div class="bg-gradient-to-br from-{{ $kpi['from'] }} to-{{ $kpi['to'] }} rounded-2xl border border-{{ $kpi['border'] }} p-4 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 bg-white/10 rounded-full -mr-8 -mt-8"></div>
        <div class="relative z-10">
            <div class="flex items-start justify-between mb-2">
                <span class="text-[10px] font-medium {{ $kpi['text'] }}">{{ $kpi['label'] }}</span>
                <svg class="w-4 h-4 {{ $kpi['subtext'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $kpi['icon'] }}"/></svg>
            </div>
            <p class="text-2xl font-bold tracking-tight text-white">{{ $kpi['value'] }}</p>
            <p class="text-[10px] {{ $kpi['subtext'] }} font-medium mt-1">{{ $kpi['sub'] }}</p>
        </div>
    </div>
    @endforeach
</div>

{{-- Markers & Data Entry --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
    {{-- Markers --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900">Markers</h3>
            </div>
            <button onclick="openModal('modal-marker')" class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 rounded-lg text-xs font-semibold transition-colors inline-flex items-center gap-1 no-print">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Marker
            </button>
        </div>
        <div class="space-y-2">
            @forelse($panel->markers as $marker)
            <div class="flex items-center justify-between p-2.5 rounded-lg hover:bg-gray-50 border border-gray-50 transition-colors">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                        <span class="text-xs font-bold text-emerald-700">{{ strtoupper(substr($marker->name, 0, 1)) }}</span>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-700">{{ $marker->name }}</p>
                        <p class="text-[10px] text-gray-400">{{ $marker->phone ?? '—' }} · {{ $marker->school->name ?? '—' }}</p>
                    </div>
                </div>
                <button onclick="removeItem('{{ route("panels.markers.destroy", [$panel, $marker]) }}', 'Remove this marker?')" class="w-7 h-7 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 flex items-center justify-center transition-colors no-print" title="Remove">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            @empty
            <div class="text-center py-6">
                <svg class="w-10 h-10 text-gray-200 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <p class="text-xs text-gray-400">No markers yet.</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Data Entry Officers --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-sky-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900">Data Entry Officers</h3>
            </div>
            <button onclick="openModal('modal-de')" class="px-3 py-1.5 bg-sky-50 hover:bg-sky-100 text-sky-600 rounded-lg text-xs font-semibold transition-colors inline-flex items-center gap-1 no-print">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Officer
            </button>
        </div>
        <div class="space-y-2">
            @forelse($panel->dataEntries as $de)
            <div class="flex items-center justify-between p-2.5 rounded-lg hover:bg-gray-50 border border-gray-50 transition-colors">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-full bg-sky-100 flex items-center justify-center flex-shrink-0">
                        <span class="text-xs font-bold text-sky-700">{{ strtoupper(substr($de->user->name, 0, 1)) }}</span>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-700">{{ $de->user->name }}</p>
                        <p class="text-[10px] text-gray-400">{{ $de->user->email }}</p>
                    </div>
                </div>
                <button onclick="removeItem('{{ route("panels.data-entries.destroy", [$panel, $de]) }}', 'Remove this officer?')" class="w-7 h-7 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 flex items-center justify-center transition-colors no-print" title="Remove">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            @empty
            <div class="text-center py-6">
                <svg class="w-10 h-10 text-gray-200 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <p class="text-xs text-gray-400">No data entry officers yet.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Assignments --}}
<div class="bg-white rounded-2xl border border-gray-100 p-5">
    <div class="flex items-center gap-2 mb-4">
        <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center">
            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <h3 class="text-sm font-bold text-gray-900">Assignments</h3>
    </div>
    <div class="space-y-2">
        @forelse($panel->assignments as $assignment)
        <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 border border-gray-50">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                    <span class="text-xs font-bold text-amber-700">{{ strtoupper(substr($assignment->user->name, 0, 1)) }}</span>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-700">{{ $assignment->user->name }}</p>
                    <p class="text-[10px] text-gray-400">{{ $assignment->district->name }} · {{ $assignment->schools->pluck('name')->implode(', ') }}</p>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-6">
            <svg class="w-10 h-10 text-gray-200 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <p class="text-xs text-gray-400">No assignments yet.</p>
        </div>
        @endforelse
    </div>
</div>

{{-- Add Marker Modal --}}
<div id="modal-marker" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 no-print">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900">Add Marker</h3>
            <button type="button" onclick="closeModal('modal-marker')" class="w-8 h-8 rounded-lg hover:bg-gray-100 flex items-center justify-center text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="{{ route('panels.markers.store', $panel) }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">Name</label><input type="text" name="name" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none"></div>
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">Phone</label><input type="text" name="phone" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none"></div>
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">School</label><select name="school_id" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none"><option value="">—</option>@foreach($schools as $school)<option value="{{ $school->id }}">{{ $school->name }}</option>@endforeach</select></div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="closeModal('modal-marker')" class="flex-1 px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50">Cancel</button>
                <button type="submit" class="flex-1 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-semibold shadow-sm">Add</button>
            </div>
        </form>
    </div>
</div>

{{-- Add Data Entry Modal --}}
<div id="modal-de" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 no-print">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-lg font-bold text-gray-900">Add Data Entry Officer</h3>
            <button type="button" onclick="closeModal('modal-de')" class="w-8 h-8 rounded-lg hover:bg-gray-100 flex items-center justify-center text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <p class="text-xs text-gray-400 mb-4">A user account will be created with a generated password.</p>
        <form action="{{ route('panels.data-entries.store', $panel) }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">Name</label><input type="text" name="name" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none"></div>
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">Email</label><input type="email" name="email" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none"></div>
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">Phone</label><input type="text" name="phone" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none"></div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="closeModal('modal-de')" class="flex-1 px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50">Cancel</button>
                <button type="submit" class="flex-1 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-semibold shadow-sm">Add & Generate Account</button>
            </div>
        </form>
    </div>
</div>

<style>
@media print {
    .no-print { display: none !important; }
    body { background: white !important; }
    .bg-gradient-to-br { background: white !important; border: 1px solid #e5e7eb !important; color: #111827 !important; }
    .bg-gradient-to-br * { color: #111827 !important; }
    .text-white { color: #111827 !important; }
    .rounded-2xl { border-radius: 0.5rem !important; }
}
</style>

<script>
function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
function closeModal(id) { document.getElementById(id).classList.add('hidden'); }
function removeItem(url, text) {
    Swal.fire({
        title: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, remove',
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
function deletePanel(url) {
    Swal.fire({
        title: 'Delete this panel?',
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
