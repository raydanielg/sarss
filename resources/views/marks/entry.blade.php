@extends('layouts.dashboard')
@section('title', 'Marks Entry - ' . config('app.name'))
@section('page_title', 'Marks Entry')

@if(isset($assignment) && isset($candidates))
@push('scripts')
<script>
const AUTO_SAVE_URL = '{{ route("marks.auto-save") }}';
const CSRF_TOKEN = '{{ csrf_token() }}';
const MAX_MARKS = {{ $subject->max_marks }};
const ASSIGNMENT_ID = {{ $assignment->id }};
const SCHOOL_ID = {{ $school->id }};
</script>
@endpush

@section('content')
<div class="mb-4 flex items-center justify-between flex-wrap gap-3">
    <div class="flex items-center gap-3">
        <a href="{{ route('marks.entry') }}" class="p-2 rounded-lg hover:bg-gray-100 text-gray-500">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h2 class="text-sm font-bold text-gray-900">{{ $assignment->panel->subject->name ?? '—' }}</h2>
            <p class="text-xs text-gray-400">{{ $assignment->panel->examination->name ?? '—' }} · {{ $assignment->district->name ?? '—' }}</p>
        </div>
    </div>
    <div class="flex items-center gap-3">
        <div id="save-indicator" class="flex items-center gap-1.5 text-xs text-gray-400">
            <span id="save-dot" class="w-2 h-2 rounded-full bg-gray-300"></span>
            <span id="save-text">Ready</span>
        </div>
        <div class="text-right">
            <p class="text-xs text-gray-400">Progress</p>
            <p class="text-sm font-bold text-emerald-600"><span id="entered-count">{{ $marks->whereNotNull('mark')->count() }}</span> / <span id="total-count">{{ $candidates->count() }}</span></p>
        </div>
    </div>
</div>

{{-- School Selector --}}
<div class="bg-white rounded-xl border border-gray-200 p-4 mb-4 shadow-sm">
    <div class="flex items-center gap-2 mb-3">
        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1"/></svg>
        <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">Select School</span>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-2">
        @foreach($schools as $s)
        @php
            $sp = $schoolProgress[$s->id] ?? ['total' => 0, 'entered' => 0];
            $pct = $sp['total'] > 0 ? round($sp['entered'] / $sp['total'] * 100) : 0;
            $isActive = $s->id === $school->id;
        @endphp
        <a href="{{ route('marks.entry', ['assignment_id' => $assignment->id, 'school_id' => $s->id]) }}"
           class="relative rounded-lg border p-3 transition-all {{ $isActive ? 'border-emerald-500 bg-emerald-50 ring-2 ring-emerald-100' : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50' }}">
            <div class="flex items-start justify-between mb-2">
                <p class="text-xs font-bold text-gray-900 truncate flex-1">{{ $s->name }}</p>
                @if($isActive)
                <svg class="w-4 h-4 text-emerald-600 shrink-0 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                @endif
            </div>
            <p class="text-[10px] text-gray-400">{{ $s->district->name ?? '—' }}</p>
            <div class="mt-2 flex items-center gap-2">
                <div class="flex-1 bg-gray-100 rounded-full h-1.5 overflow-hidden">
                    <div class="h-1.5 rounded-full {{ $pct >= 100 ? 'bg-emerald-500' : ($pct > 0 ? 'bg-gold-400' : 'bg-gray-200') }}" style="width: {{ $pct }}%"></div>
                </div>
                <span class="text-[10px] font-semibold {{ $pct >= 100 ? 'text-emerald-600' : 'text-gray-400' }}">{{ $sp['entered'] }}/{{ $sp['total'] }}</span>
            </div>
        </a>
        @endforeach
    </div>
</div>

{{-- Current School Header --}}
<div class="bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-xl p-4 mb-4 text-white">
    <div class="flex items-center justify-between flex-wrap gap-2">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1"/></svg>
            </div>
            <div>
                <h3 class="text-sm font-bold">{{ $school->name }}</h3>
                <p class="text-[10px] text-white/70">{{ $school->district->name ?? '' }} · {{ $subject->name }} · Max {{ $subject->max_marks }}</p>
            </div>
        </div>
        <div class="text-right">
            <p class="text-[10px] text-white/70">Completion</p>
            <p class="text-lg font-bold">{{ $marks->whereNotNull('mark')->count() }}<span class="text-white/60 text-sm">/{{ $candidates->count() }}</span></p>
        </div>
    </div>
    <div class="w-full bg-white/20 rounded-full h-1.5 mt-3">
        <div id="progress-bar" class="bg-gold-400 h-1.5 rounded-full transition-all duration-300" style="width: {{ $candidates->count() > 0 ? ($marks->whereNotNull('mark')->count()/$candidates->count()*100) : 0 }}%"></div>
    </div>
</div>

{{-- Spreadsheet --}}
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-sm marks-spreadsheet">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="px-4 py-2.5 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider w-10">#</th>
                    <th class="px-4 py-2.5 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Candidate No.</th>
                    <th class="px-4 py-2.5 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-4 py-2.5 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Gender</th>
                    <th class="px-4 py-2.5 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider w-28">Mark <span class="text-gray-300 font-normal normal-case">/ {{ $subject->max_marks }}</span></th>
                    <th class="px-4 py-2.5 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider w-24">Status</th>
                </tr>
            </thead>
            <tbody>
                @if($candidates->isEmpty())
                <tr><td colspan="6" class="px-5 py-12 text-center">
                    <svg class="w-10 h-10 text-gray-200 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <p class="text-sm text-gray-400">No candidates found for this school.</p>
                    <p class="text-xs text-gray-300 mt-1">Make sure candidates have been imported for this examination.</p>
                </td></tr>
                @else
                @php $rowIndex = 0; @endphp
                @foreach($candidates as $candidate)
                @php
                    $mark = $marks->get($candidate->id);
                    $isLocked = $mark && in_array($mark->status, ['verified', 'locked']);
                @endphp
                <tr class="border-b border-gray-50 hover:bg-emerald-50/30 transition-colors {{ $rowIndex % 2 === 0 ? '' : 'bg-gray-50/20' }}" data-row="{{ $rowIndex }}">
                    <td class="px-4 py-2 text-[10px] text-gray-300 font-mono">{{ $rowIndex + 1 }}</td>
                    <td class="px-4 py-2 font-mono text-xs text-gray-700">{{ $candidate->candidate_number }}</td>
                    <td class="px-4 py-2 font-semibold text-gray-900 text-xs">{{ $candidate->name }}</td>
                    <td class="px-4 py-2 text-gray-500 text-xs">{{ $candidate->gender ? ucfirst($candidate->gender) : '—' }}</td>
                    <td class="px-2 py-1">
                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            max="{{ $subject->max_marks }}"
                            value="{{ $mark?->mark }}"
                            data-candidate-id="{{ $candidate->id }}"
                            data-row="{{ $rowIndex }}"
                            class="mark-input w-20 px-2 py-1.5 border border-gray-200 rounded-md text-sm text-center font-semibold outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition-all {{ $isLocked ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-900 hover:border-gray-300' }}"
                            placeholder="—"
                            {{ $isLocked ? 'disabled' : '' }}
                        >
                    </td>
                    <td class="px-4 py-2">
                        @if($mark?->status === 'verified')
                            <span class="inline-flex items-center gap-1 text-[10px] font-medium text-emerald-600"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Verified</span>
                        @elseif($mark?->status === 'entered')
                            <span class="inline-flex items-center gap-1 text-[10px] font-medium text-sky-600"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Saved</span>
                        @elseif($mark?->status === 'rejected')
                            <span class="inline-flex items-center gap-1 text-[10px] font-medium text-red-500" title="{{ $mark->rejection_reason }}"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>Rejected</span>
                        @elseif($mark?->status === 'locked')
                            <span class="inline-flex items-center gap-1 text-[10px] font-medium text-gray-400"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>Locked</span>
                        @else
                            <span class="text-[10px] text-gray-300">Pending</span>
                        @endif
                    </td>
                </tr>
                @php $rowIndex++; @endphp
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4 flex items-center justify-between flex-wrap gap-3">
    <div class="flex items-center gap-4 text-[10px] text-gray-400 flex-wrap">
        <span class="flex items-center gap-1"><kbd class="px-1.5 py-0.5 bg-gray-100 border border-gray-200 rounded text-[9px] font-mono">Enter</kbd> Move down</span>
        <span class="flex items-center gap-1"><kbd class="px-1.5 py-0.5 bg-gray-100 border border-gray-200 rounded text-[9px] font-mono">↑↓</kbd> Navigate</span>
        <span class="flex items-center gap-1"><kbd class="px-1.5 py-0.5 bg-gray-100 border border-gray-200 rounded text-[9px] font-mono">Tab</kbd> Next cell</span>
        <span class="flex items-center gap-1 text-emerald-500"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Auto-saves on Enter/blur</span>
    </div>
    <form action="{{ route('marks.save') }}" method="POST" id="bulk-save-form">
        @csrf
        <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">
        <input type="hidden" name="school_id" value="{{ $school->id }}">
        <div id="hidden-marks-container"></div>
        <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold transition-colors inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            Save All
        </button>
    </form>
</div>

@push('scripts')
<script>
(function() {
    const inputs = Array.from(document.querySelectorAll('.mark-input'));

    function setSaveStatus(status) {
        const dot = document.getElementById('save-dot');
        const text = document.getElementById('save-text');
        const colors = { idle: 'bg-gray-300', saving: 'bg-amber-400 animate-pulse', saved: 'bg-emerald-500', error: 'bg-red-500' };
        const labels = { idle: 'Ready', saving: 'Saving...', saved: 'Saved', error: 'Error' };
        dot.className = 'w-2 h-2 rounded-full ' + (colors[status] || colors.idle);
        text.textContent = labels[status] || labels.idle;
        text.className = 'text-xs ' + (status === 'error' ? 'text-red-500' : status === 'saved' ? 'text-emerald-600' : 'text-gray-400');
    }

    function updateProgress(entered, total) {
        document.getElementById('entered-count').textContent = entered;
        document.getElementById('total-count').textContent = total;
        const pct = total > 0 ? (entered / total * 100) : 0;
        document.getElementById('progress-bar').style.width = pct + '%';
    }

    function autoSave(input) {
        const candidateId = input.dataset.candidateId;
        const markValue = input.value.trim();

        if (markValue !== '' && parseFloat(markValue) > MAX_MARKS) {
            input.classList.add('border-red-500', 'ring-2', 'ring-red-100');
            showToast('error', 'Invalid Mark', 'Mark cannot exceed ' + MAX_MARKS);
            setSaveStatus('error');
            return;
        }
        input.classList.remove('border-red-500', 'ring-2', 'ring-red-100');
        setSaveStatus('saving');

        fetch(AUTO_SAVE_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
            body: JSON.stringify({ assignment_id: ASSIGNMENT_ID, school_id: SCHOOL_ID, candidate_id: parseInt(candidateId), mark: markValue === '' ? null : parseFloat(markValue) })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                setSaveStatus('saved');
                updateProgress(data.entered, data.total);
                const statusCell = input.closest('tr').querySelector('td:last-child');
                if (markValue !== '') {
                    statusCell.innerHTML = '<span class="inline-flex items-center gap-1 text-[10px] font-medium text-sky-600"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Saved</span>';
                } else {
                    statusCell.innerHTML = '<span class="text-[10px] text-gray-300">Pending</span>';
                }
                input.classList.add('border-emerald-400');
                setTimeout(() => input.classList.remove('border-emerald-400'), 1000);
                setTimeout(() => setSaveStatus('idle'), 1500);
            } else {
                setSaveStatus('error');
                showToast('error', 'Save Failed', data.message || 'Could not save mark');
            }
        })
        .catch(err => { setSaveStatus('error'); showToast('error', 'Network Error', 'Could not connect to server'); });
    }

    function focusRow(rowIndex) {
        const target = inputs.find(i => parseInt(i.dataset.row) === rowIndex);
        if (target) { target.focus(); target.select(); }
    }

    inputs.forEach((input, idx) => {
        input.addEventListener('keydown', function(e) {
            const currentRow = parseInt(this.dataset.row);
            if (e.key === 'Enter') {
                e.preventDefault(); autoSave(this);
                if (idx < inputs.length - 1) focusRow(currentRow + 1);
            } else if (e.key === 'ArrowDown') {
                e.preventDefault();
                if (idx < inputs.length - 1) { inputs[idx + 1].focus(); inputs[idx + 1].select(); }
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                if (idx > 0) { inputs[idx - 1].focus(); inputs[idx - 1].select(); }
            }
        });
        input.addEventListener('blur', function() {
            if (this.value.trim() !== '' || this.dataset.hadValue === 'true') autoSave(this);
        });
        input.addEventListener('focus', function() { this.dataset.hadValue = this.value.trim() !== '' ? 'true' : 'false'; this.select(); });
        input.addEventListener('input', function() {
            const val = parseFloat(this.value);
            if (this.value.trim() !== '' && val > MAX_MARKS) { this.classList.add('border-red-500', 'ring-2', 'ring-red-100'); }
            else { this.classList.remove('border-red-500', 'ring-2', 'ring-red-100'); }
        });
    });

    document.getElementById('bulk-save-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const container = document.getElementById('hidden-marks-container');
        container.innerHTML = '';
        inputs.forEach(input => {
            if (!input.disabled) {
                const cid = input.dataset.candidateId;
                container.innerHTML += '<input type="hidden" name="marks[' + cid + '][candidate_id]" value="' + cid + '">';
                container.innerHTML += '<input type="hidden" name="marks[' + cid + '][mark]" value="' + (input.value || '') + '">';
            }
        });
        this.submit();
    });

    if (inputs.length > 0) { inputs[0].focus(); inputs[0].select(); }
})();
</script>
@endpush

@elseif(isset($assignment) && isset($schools))
@section('content')
<div class="mb-4 flex items-center gap-3">
    <a href="{{ route('marks.entry') }}" class="p-2 rounded-lg hover:bg-gray-100 text-gray-500">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <div>
        <h2 class="text-sm font-bold text-gray-900">{{ $assignment->panel->subject->name ?? '—' }}</h2>
        <p class="text-xs text-gray-400">{{ $assignment->panel->examination->name ?? '—' }} · {{ $assignment->district->name ?? '—' }}</p>
    </div>
</div>

<div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
    <div class="flex items-center gap-2 mb-3">
        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/></svg>
        <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">Select School to Begin</span>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2">
        @foreach($schools as $s)
        <a href="{{ route('marks.entry', ['assignment_id' => $assignment->id, 'school_id' => $s->id]) }}"
           class="rounded-lg border border-gray-200 p-3 hover:border-emerald-400 hover:bg-emerald-50/30 transition-all">
            <p class="text-xs font-bold text-gray-900 truncate">{{ $s->name }}</p>
            <p class="text-[10px] text-gray-400 mt-0.5">{{ $s->district->name ?? '—' }}</p>
        </a>
        @endforeach
    </div>
</div>

@else
@section('content')
<div class="mb-6">
    <p class="text-sm text-gray-500">Select an assignment to begin entering marks.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($assignments as $assignment)
    <a href="{{ route('marks.entry', ['assignment_id' => $assignment->id]) }}" class="bg-white rounded-xl border border-gray-100 p-5 hover:shadow-lg transition-shadow group">
        <div class="flex items-start justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center group-hover:bg-emerald-100 transition-colors">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
        </div>
        <h3 class="text-sm font-bold text-gray-900">{{ $assignment->panel->subject->name ?? '—' }}</h3>
        <p class="text-xs text-gray-400 mt-1">{{ $assignment->panel->examination->name ?? '—' }}</p>
        <p class="text-xs text-gray-400 mt-2">{{ $assignment->district->name ?? '—' }}</p>
        <div class="mt-3 flex flex-wrap gap-1">
            @foreach($assignment->schools as $school)<span class="px-2 py-0.5 bg-gray-50 rounded text-[10px] text-gray-500">{{ $school->name }}</span>@endforeach
        </div>
    </a>
    @empty
    <div class="col-span-full bg-white rounded-xl border border-gray-100 p-8 text-center"><p class="text-sm text-gray-400">No assignments yet. Contact your moderator.</p></div>
    @endforelse
</div>
@endif
@endsection
