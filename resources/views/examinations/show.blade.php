@extends('layouts.dashboard')
@section('title', $examination->name . ' - ' . config('app.name'))
@section('page_title', $examination->name)

@section('content')
<div class="mb-6">
    <a href="{{ route('examinations.index') }}" class="text-xs text-gray-400 hover:text-gray-600 inline-flex items-center gap-1">&larr; Back to Examinations</a>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
    @foreach([
        ['label'=>'Schools','value'=>$stats['schools'],'icon'=>'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1','color'=>'emerald'],
        ['label'=>'Candidates','value'=>$stats['candidates'],'icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857','color'=>'amber'],
        ['label'=>'Subjects','value'=>$stats['subjects'],'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2','color'=>'sky'],
        ['label'=>'Panels','value'=>$stats['panels'],'icon'=>'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2','color'=>'violet']
    ] as $card)
    <div class="bg-gradient-to-br from-{{ $card['color'] }}-500 to-{{ $card['color'] }}-700 rounded-xl p-4 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 bg-white/10 rounded-full -mr-8 -mt-8"></div>
        <div class="relative z-10">
            <div class="flex items-start justify-between mb-2">
                <span class="text-[10px] font-medium text-white/80">{{ $card['label'] }}</span>
                <svg class="w-4 h-4 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/></svg>
            </div>
            <p class="text-2xl font-bold">{{ $card['value'] }}</p>
        </div>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-3">Progress</h3>
        <div class="space-y-3">
            <div><div class="flex justify-between text-xs mb-1"><span class="text-gray-500">Marks Entered</span><span class="font-semibold text-gray-900">{{ $stats['marks_entered'] }} / {{ $stats['total_marks'] }}</span></div><div class="w-full bg-gray-100 rounded-full h-2"><div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $stats['total_marks'] > 0 ? ($stats['marks_entered']/$stats['total_marks']*100) : 0 }}%"></div></div></div>
            <div><div class="flex justify-between text-xs mb-1"><span class="text-gray-500">Marks Verified</span><span class="font-semibold text-gray-900">{{ $stats['marks_verified'] }} / {{ $stats['total_marks'] }}</span></div><div class="w-full bg-gray-100 rounded-full h-2"><div class="bg-gold-400 h-2 rounded-full" style="width: {{ $stats['total_marks'] > 0 ? ($stats['marks_verified']/$stats['total_marks']*100) : 0 }}%"></div></div></div>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-3">Details</h3>
        <div class="space-y-2 text-xs">
            <div class="flex justify-between"><span class="text-gray-400">Type</span><span class="font-medium text-gray-700">{{ $examination->examType->name ?? '—' }}</span></div>
            <div class="flex justify-between"><span class="text-gray-400">Year</span><span class="font-medium text-gray-700">{{ $examination->academicYear->year ?? '—' }}</span></div>
            <div class="flex justify-between"><span class="text-gray-400">Region</span><span class="font-medium text-gray-700">{{ $examination->region->name ?? '—' }}</span></div>
            <div class="flex justify-between"><span class="text-gray-400">Dates</span><span class="font-medium text-gray-700">{{ $examination->start_date?->format('d M') }} - {{ $examination->end_date?->format('d M') }}</span></div>
            <div class="flex justify-between"><span class="text-gray-400">Markers</span><span class="font-medium text-gray-700">{{ $stats['markers'] }}</span></div>
            <div class="flex justify-between"><span class="text-gray-400">Data Entry</span><span class="font-medium text-gray-700">{{ $stats['data_entries'] }}</span></div>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-3">Panels</h3>
        <div class="space-y-2">
            @forelse($examination->panels as $panel)
            <a href="{{ route('panels.show', $panel) }}" class="flex items-center justify-between text-xs hover:bg-gray-50 rounded-lg p-2 transition-colors">
                <span class="font-medium text-gray-700">{{ $panel->subject->name ?? '—' }}</span>
                <span class="text-gray-400">{{ $panel->moderator->name ?? '—' }}</span>
            </a>
            @empty
            <p class="text-xs text-gray-400 text-center py-4">No panels yet.</p>
            @endforelse
        </div>
    </div>
</div>

<div class="flex gap-2">
    <a href="{{ route('examinations.edit', $examination) }}" class="px-4 py-2 bg-gray-50 text-gray-600 rounded-lg text-xs font-medium hover:bg-gray-100">Edit</a>
    <a href="{{ route('reports.overall', $examination) }}" class="px-4 py-2 bg-emerald-50 text-emerald-700 rounded-lg text-xs font-medium hover:bg-emerald-100">Reports</a>
</div>
@endsection
