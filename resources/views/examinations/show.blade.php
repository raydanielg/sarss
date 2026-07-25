@extends('layouts.dashboard')
@section('title', $examination->name . ' - ' . config('app.name'))
@section('page_title', $examination->name)

@section('content')
<div class="mb-6 flex items-center justify-between">
    <a href="{{ route('examinations.index') }}" class="text-xs text-gray-400 hover:text-gray-600 inline-flex items-center gap-1">&larr; Back to Examinations</a>
    <div class="flex gap-2">
        <a href="{{ route('examinations.edit', $examination) }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-600 rounded-lg text-xs font-semibold hover:bg-gray-50 transition-colors inline-flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit
        </a>
        <a href="{{ route('reports.overall', $examination) }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold transition-colors inline-flex items-center gap-1.5 shadow-sm">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Reports
        </a>
    </div>
</div>

@php
    $enterPct = $stats['total_marks'] > 0 ? round($stats['marks_entered']/$stats['total_marks']*100) : 0;
    $verifyPct = $stats['total_marks'] > 0 ? round($stats['marks_verified']/$stats['total_marks']*100) : 0;
@endphp

<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
    @php
    $cards = [
        ['label'=>'Schools','value'=>$stats['schools'],'sub'=>'Participating','icon'=>'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1','from'=>'emerald-600','to'=>'emerald-700','border'=>'emerald-500','text'=>'emerald-100','subtext'=>'emerald-200'],
        ['label'=>'Candidates','value'=>$stats['candidates'],'sub'=>'Registered','icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z','from'=>'amber-400','to'=>'amber-500','border'=>'amber-300','text'=>'amber-50','subtext'=>'amber-100'],
        ['label'=>'Subjects','value'=>$stats['subjects'],'sub'=>'Examinable','icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2','from'=>'sky-500','to'=>'sky-600','border'=>'sky-400','text'=>'sky-100','subtext'=>'sky-200'],
        ['label'=>'Panels','value'=>$stats['panels'],'sub'=>'Subject panels','icon'=>'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10','from'=>'violet-500','to'=>'violet-600','border'=>'violet-400','text'=>'violet-100','subtext'=>'violet-200'],
    ];
    @endphp
    @foreach($cards as $card)
    <div class="bg-gradient-to-br from-{{ $card['from'] }} to-{{ $card['to'] }} rounded-2xl border border-{{ $card['border'] }} p-4 text-white relative overflow-hidden hover:shadow-lg transition-shadow">
        <div class="absolute top-0 right-0 w-16 h-16 bg-white/10 rounded-full -mr-8 -mt-8"></div>
        <div class="relative z-10">
            <div class="flex items-start justify-between mb-2">
                <span class="text-[10px] font-medium {{ $card['text'] }}">{{ $card['label'] }}</span>
                <svg class="w-4 h-4 {{ $card['subtext'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/></svg>
            </div>
            <p class="text-2xl font-bold tracking-tight text-white">{{ $card['value'] }}</p>
            <p class="text-[10px] {{ $card['subtext'] }} font-medium mt-1">{{ $card['sub'] }}</p>
        </div>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
    {{-- Progress Card --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-5">
        <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <h3 class="text-sm font-bold text-gray-900">Progress</h3>
        </div>
        <div class="space-y-4">
            <div>
                <div class="flex justify-between text-xs mb-1.5">
                    <span class="text-gray-500 font-medium">Marks Entered</span>
                    <span class="font-bold text-gray-900">{{ $stats['marks_entered'] }} / {{ $stats['total_marks'] }}</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                    <div class="bg-gradient-to-r from-emerald-400 to-emerald-600 h-2.5 rounded-full transition-all duration-500" style="width: {{ $enterPct }}%"></div>
                </div>
                <p class="text-[10px] text-gray-400 mt-1 text-right font-medium">{{ $enterPct }}%</p>
            </div>
            <div>
                <div class="flex justify-between text-xs mb-1.5">
                    <span class="text-gray-500 font-medium">Marks Verified</span>
                    <span class="font-bold text-gray-900">{{ $stats['marks_verified'] }} / {{ $stats['total_marks'] }}</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                    <div class="bg-gradient-to-r from-gold-400 to-gold-500 h-2.5 rounded-full transition-all duration-500" style="width: {{ $verifyPct }}%"></div>
                </div>
                <p class="text-[10px] text-gray-400 mt-1 text-right font-medium">{{ $verifyPct }}%</p>
            </div>
        </div>
    </div>

    {{-- Details Card --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-5">
        <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 rounded-lg bg-sky-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-sm font-bold text-gray-900">Details</h3>
        </div>
        <div class="space-y-2.5 text-xs">
            <div class="flex justify-between items-center py-1 border-b border-gray-50"><span class="text-gray-400">Type</span><span class="font-semibold text-gray-700">{{ $examination->examType->name ?? '—' }}</span></div>
            <div class="flex justify-between items-center py-1 border-b border-gray-50"><span class="text-gray-400">Year</span><span class="font-semibold text-gray-700">{{ $examination->academicYear->year ?? '—' }}</span></div>
            <div class="flex justify-between items-center py-1 border-b border-gray-50"><span class="text-gray-400">Region</span><span class="font-semibold text-gray-700">{{ $examination->region->name ?? '—' }}</span></div>
            <div class="flex justify-between items-center py-1 border-b border-gray-50"><span class="text-gray-400">Dates</span><span class="font-semibold text-gray-700">{{ $examination->start_date?->format('d M') }} - {{ $examination->end_date?->format('d M') }}</span></div>
            <div class="flex justify-between items-center py-1 border-b border-gray-50"><span class="text-gray-400">Markers</span><span class="font-semibold text-gray-700">{{ $stats['markers'] }}</span></div>
            <div class="flex justify-between items-center py-1"><span class="text-gray-400">Data Entry</span><span class="font-semibold text-gray-700">{{ $stats['data_entries'] }}</span></div>
        </div>
    </div>

    {{-- Panels Card --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-5">
        <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 rounded-lg bg-violet-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
            <h3 class="text-sm font-bold text-gray-900">Panels</h3>
        </div>
        <div class="space-y-1.5">
            @forelse($examination->panels as $panel)
            <a href="{{ route('panels.show', $panel) }}" class="flex items-center justify-between text-xs hover:bg-gray-50 rounded-lg p-2.5 transition-colors border border-gray-50 hover:border-gray-100">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-md bg-violet-50 flex items-center justify-center flex-shrink-0">
                        <svg class="w-3 h-3 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <span class="font-semibold text-gray-700">{{ $panel->subject->name ?? '—' }}</span>
                </div>
                <span class="text-gray-400 text-[10px]">{{ $panel->moderator->name ?? '—' }}</span>
            </a>
            @empty
            <div class="text-center py-6">
                <svg class="w-10 h-10 text-gray-200 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                <p class="text-xs text-gray-400">No panels yet.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
