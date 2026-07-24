@extends('layouts.dashboard')
@section('title', 'Marks Entry - ' . config('app.name'))
@section('page_title', 'Marks Entry')

@section('content')
@if(isset($assignment) && isset($candidates))
<div class="mb-6">
    <a href="{{ route('marks.entry') }}" class="text-xs text-gray-400 hover:text-gray-600">&larr; Back to Assignments</a>
</div>

<div class="bg-white rounded-xl border border-gray-100 p-5 mb-4">
    <div class="flex items-center justify-between flex-wrap gap-2">
        <div>
            <h2 class="text-sm font-bold text-gray-900">{{ $school->name }}</h2>
            <p class="text-xs text-gray-400">{{ $subject->name }} · {{ $assignment->district->name }}</p>
        </div>
        <div class="text-right">
            <p class="text-xs text-gray-400">Progress</p>
            <p class="text-sm font-bold text-emerald-600">{{ $marks->whereNotNull('mark')->count() }} / {{ $candidates->count() }}</p>
        </div>
    </div>
    <div class="w-full bg-gray-100 rounded-full h-2 mt-3">
        <div class="bg-emerald-500 h-2 rounded-full transition-all" style="width: {{ $candidates->count() > 0 ? ($marks->whereNotNull('mark')->count()/$candidates->count()*100) : 0 }}%"></div>
    </div>
</div>

<form action="{{ route('marks.save') }}" method="POST">
    @csrf
    <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">
    <input type="hidden" name="school_id" value="{{ $school->id }}">
    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead><tr class="text-left text-xs text-gray-500 bg-gray-50/50 border-b">
                <th class="px-5 py-3 font-medium">Candidate No.</th>
                <th class="px-5 py-3 font-medium">Name</th>
                <th class="px-5 py-3 font-medium w-32">Mark (max {{ $subject->max_marks }})</th>
                <th class="px-5 py-3 font-medium">Status</th>
            </tr></thead>
            <tbody>
                @foreach($candidates as $candidate)
                @php $mark = $marks->get($candidate->id); @endphp
                <tr class="border-b border-gray-50">
                    <td class="px-5 py-2.5 font-mono text-xs text-gray-700">{{ $candidate->candidate_number }}</td>
                    <td class="px-5 py-2.5 font-semibold text-gray-900">{{ $candidate->name }}</td>
                    <td class="px-5 py-2.5"><input type="number" name="marks[{{ $candidate->id }}][mark]" step="0.01" min="0" max="{{ $subject->max_marks }}" value="{{ $mark?->mark }}" class="w-24 px-2 py-1 border border-gray-200 rounded-lg text-sm outline-none focus:border-emerald-400" {{ $mark && $mark->status === 'locked' ? 'disabled' : '' }}><input type="hidden" name="marks[{{ $candidate->id }}][candidate_id]" value="{{ $candidate->id }}"></td>
                    <td class="px-5 py-2.5">
                        @if($mark?->status === 'verified')<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">Verified</span>
                        @elseif($mark?->status === 'entered')<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-sky-50 text-sky-700 border border-sky-100">Entered</span>
                        @elseif($mark?->status === 'rejected')<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-red-50 text-red-700 border border-red-100" title="{{ $mark->rejection_reason }}">Rejected</span>
                        @elseif($mark?->status === 'locked')<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-gray-100 text-gray-500">Locked</span>
                        @else<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-gray-50 text-gray-400">Pending</span>@endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        <button type="submit" class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-semibold">Save Marks</button>
    </div>
</form>

@else
<div class="mb-6">
    <p class="text-sm text-gray-500">Select an assignment to begin entering marks.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($assignments as $assignment)
    <a href="{{ route('marks.entry', ['assignment_id' => $assignment->id]) }}" class="bg-white rounded-xl border border-gray-100 p-5 hover:shadow-lg transition-shadow">
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
