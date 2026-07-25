@extends('layouts.dashboard')
@section('title', 'Reports - ' . config('app.name'))
@section('page_title', 'Reports & Analytics')

@section('content')
<div class="mb-6"><p class="text-sm text-gray-500">Select an examination to view reports.</p></div>

@php
    $cardThemes = [
        ['from'=>'emerald-600','to'=>'emerald-700','border'=>'emerald-500','statBg'=>'emerald-800/30','subText'=>'emerald-100'],
        ['from'=>'sky-500','to'=>'sky-600','border'=>'sky-400','statBg'=>'sky-800/30','subText'=>'sky-100'],
        ['from'=>'violet-500','to'=>'violet-600','border'=>'violet-400','statBg'=>'violet-800/30','subText'=>'violet-100'],
        ['from'=>'amber-400','to'=>'amber-500','border'=>'amber-300','statBg'=>'amber-800/30','subText'=>'amber-100'],
        ['from'=>'rose-500','to'=>'rose-600','border'=>'rose-400','statBg'=>'rose-800/30','subText'=>'rose-100'],
        ['from'=>'teal-500','to'=>'teal-600','border'=>'teal-400','statBg'=>'teal-800/30','subText'=>'teal-100'],
    ];
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($examinations as $i => $exam)
    @php $theme = $cardThemes[$i % count($cardThemes)]; @endphp
    <div class="bg-gradient-to-br from-{{ $theme['from'] }} to-{{ $theme['to'] }} rounded-2xl border border-{{ $theme['border'] }} p-5 text-white relative overflow-hidden hover:shadow-xl transition-all duration-300 hover:-translate-y-0.5">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
        <div class="absolute bottom-0 left-0 w-16 h-16 bg-white/5 rounded-full -ml-8 -mb-8"></div>
        <div class="relative z-10">
            <div class="flex items-start gap-2.5 mb-4">
                <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-white tracking-tight">{{ $exam->name }}</h3>
                    <p class="text-[10px] {{ $theme['subText'] }} mt-0.5">{{ $exam->academicYear->year ?? '' }} · {{ $exam->examType->name ?? '' }}</p>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-2 mb-4">
                <div class="text-center {{ $theme['statBg'] }} rounded-lg py-2 backdrop-blur-sm">
                    <p class="text-lg font-bold text-white">{{ $exam->candidates_count }}</p>
                    <p class="text-[9px] {{ $theme['subText'] }} font-medium">Candidates</p>
                </div>
                <div class="text-center {{ $theme['statBg'] }} rounded-lg py-2 backdrop-blur-sm">
                    <p class="text-lg font-bold text-white">{{ $exam->schools_count }}</p>
                    <p class="text-[9px] {{ $theme['subText'] }} font-medium">Schools</p>
                </div>
                <div class="text-center {{ $theme['statBg'] }} rounded-lg py-2 backdrop-blur-sm">
                    <p class="text-lg font-bold text-white">{{ $exam->subjects_count }}</p>
                    <p class="text-[9px] {{ $theme['subText'] }} font-medium">Subjects</p>
                </div>
            </div>
            <a href="{{ route('reports.overall', $exam) }}" class="w-full text-center px-3 py-2 bg-white text-{{ $theme['from'] }} rounded-lg text-xs font-bold hover:bg-white/90 transition-colors flex items-center justify-center gap-1.5 mb-2">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                View Overall Report
            </a>
            <div class="grid grid-cols-2 gap-1.5">
                <a href="{{ route('reports.school', $exam) }}" class="text-center px-3 py-1.5 bg-white/15 hover:bg-white/25 text-white rounded-lg text-xs font-medium transition-colors">School</a>
                <a href="{{ route('reports.subject', $exam) }}" class="text-center px-3 py-1.5 bg-white/15 hover:bg-white/25 text-white rounded-lg text-xs font-medium transition-colors">Subject</a>
                <a href="{{ route('reports.district', $exam) }}" class="text-center px-3 py-1.5 bg-white/15 hover:bg-white/25 text-white rounded-lg text-xs font-medium transition-colors">District</a>
                <a href="{{ route('reports.users', $exam) }}" class="text-center px-3 py-1.5 bg-white/15 hover:bg-white/25 text-white rounded-lg text-xs font-medium transition-colors">Users</a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full bg-white rounded-2xl border border-gray-100 p-12 text-center">
        <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        <p class="text-sm text-gray-400">No examinations found.</p>
    </div>
    @endforelse
</div>
@endsection
