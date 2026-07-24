@extends('layouts.dashboard')
@section('title', 'Examinations - ' . config('app.name'))
@section('page_title', 'Examinations')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <p class="text-sm text-gray-500">Manage all examinations.</p>
    <a href="{{ route('examinations.create') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg transition-colors inline-flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Create Examination
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($examinations as $exam)
    <div class="bg-white rounded-xl border border-gray-100 p-5 hover:shadow-lg transition-shadow">
        <div class="flex items-start justify-between mb-3">
            <div>
                <h3 class="text-sm font-bold text-gray-900">{{ $exam->name }}</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ $exam->academicYear->year ?? '' }} · {{ $exam->examType->name ?? '' }}</p>
            </div>
            @php $statusColors = ['draft'=>'gray','open'=>'emerald','closed'=>'amber','archived'=>'red']; @endphp
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-{{ $statusColors[$exam->status] }}-50 text-{{ $statusColors[$exam->status] }}-700 border border-{{ $statusColors[$exam->status] }}-100 capitalize">{{ $exam->status }}</span>
        </div>
        <div class="grid grid-cols-3 gap-2 mb-4">
            <div class="text-center bg-gray-50 rounded-lg py-2"><p class="text-lg font-bold text-gray-900">{{ $exam->candidates_count }}</p><p class="text-[10px] text-gray-400">Candidates</p></div>
            <div class="text-center bg-gray-50 rounded-lg py-2"><p class="text-lg font-bold text-gray-900">{{ $exam->schools_count }}</p><p class="text-[10px] text-gray-400">Schools</p></div>
            <div class="text-center bg-gray-50 rounded-lg py-2"><p class="text-lg font-bold text-gray-900">{{ $exam->subjects_count }}</p><p class="text-[10px] text-gray-400">Subjects</p></div>
        </div>
        <div class="flex items-center gap-2 text-xs">
            <a href="{{ route('examinations.show', $exam) }}" class="flex-1 text-center px-3 py-1.5 bg-emerald-50 text-emerald-700 rounded-lg font-medium hover:bg-emerald-100 transition-colors">View</a>
            <a href="{{ route('examinations.edit', $exam) }}" class="flex-1 text-center px-3 py-1.5 bg-gray-50 text-gray-600 rounded-lg font-medium hover:bg-gray-100 transition-colors">Edit</a>
            @if($exam->status === 'draft')
            <form action="{{ route('examinations.status', [$exam, 'open']) }}" method="POST" class="inline">@csrf<button class="px-3 py-1.5 bg-gold-50 text-gold-600 rounded-lg font-medium hover:bg-gold-100 transition-colors">Open</button></form>
            @elseif($exam->status === 'open')
            <form action="{{ route('examinations.status', [$exam, 'closed']) }}" method="POST" class="inline">@csrf<button class="px-3 py-1.5 bg-amber-50 text-amber-600 rounded-lg font-medium hover:bg-amber-100 transition-colors">Close</button></form>
            @endif
        </div>
    </div>
    @empty
    <div class="col-span-full bg-white rounded-xl border border-gray-100 p-8 text-center">
        <p class="text-sm text-gray-400">No examinations yet. Click "Create Examination" to get started.</p>
    </div>
    @endforelse
</div>
@endsection
