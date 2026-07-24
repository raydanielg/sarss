@extends('layouts.dashboard')
@section('title', 'Reports - ' . config('app.name'))
@section('page_title', 'Reports & Analytics')

@section('content')
<div class="mb-6"><p class="text-sm text-gray-500">Select an examination to view reports.</p></div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($examinations as $exam)
    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <h3 class="text-sm font-bold text-gray-900">{{ $exam->name }}</h3>
        <p class="text-xs text-gray-400 mt-1">{{ $exam->academicYear->year ?? '' }} · {{ $exam->examType->name ?? '' }}</p>
        <div class="grid grid-cols-3 gap-2 my-4">
            <div class="text-center bg-gray-50 rounded-lg py-2"><p class="text-sm font-bold text-gray-900">{{ $exam->candidates_count }}</p><p class="text-[10px] text-gray-400">Candidates</p></div>
            <div class="text-center bg-gray-50 rounded-lg py-2"><p class="text-sm font-bold text-gray-900">{{ $exam->schools_count }}</p><p class="text-[10px] text-gray-400">Schools</p></div>
            <div class="text-center bg-gray-50 rounded-lg py-2"><p class="text-sm font-bold text-gray-900">{{ $exam->subjects_count }}</p><p class="text-[10px] text-gray-400">Subjects</p></div>
        </div>
        <div class="grid grid-cols-2 gap-1.5">
            <a href="{{ route('reports.overall', $exam) }}" class="text-center px-3 py-1.5 bg-emerald-50 text-emerald-700 rounded-lg text-xs font-medium hover:bg-emerald-100">Overall</a>
            <a href="{{ route('reports.school', $exam) }}" class="text-center px-3 py-1.5 bg-gray-50 text-gray-600 rounded-lg text-xs font-medium hover:bg-gray-100">School</a>
            <a href="{{ route('reports.subject', $exam) }}" class="text-center px-3 py-1.5 bg-gray-50 text-gray-600 rounded-lg text-xs font-medium hover:bg-gray-100">Subject</a>
            <a href="{{ route('reports.district', $exam) }}" class="text-center px-3 py-1.5 bg-gray-50 text-gray-600 rounded-lg text-xs font-medium hover:bg-gray-100">District</a>
            <a href="{{ route('reports.users', $exam) }}" class="text-center px-3 py-1.5 bg-gray-50 text-gray-600 rounded-lg text-xs font-medium hover:bg-gray-100 col-span-2">User Performance</a>
        </div>
    </div>
    @empty
    <div class="col-span-full bg-white rounded-xl border border-gray-100 p-8 text-center"><p class="text-sm text-gray-400">No examinations found.</p></div>
    @endforelse
</div>
@endsection
