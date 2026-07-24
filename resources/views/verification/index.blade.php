@extends('layouts.dashboard')
@section('title', 'Verification - ' . config('app.name'))
@section('page_title', 'Verification & Moderation')

@section('content')
<div class="mb-6"><p class="text-sm text-gray-500">Review and verify marks for your subject panels.</p></div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($panels as $panel)
    <a href="{{ route('verification.show', $panel) }}" class="bg-white rounded-xl border border-gray-100 p-5 hover:shadow-lg transition-shadow">
        <h3 class="text-sm font-bold text-gray-900">{{ $panel->subject->name ?? '—' }}</h3>
        <p class="text-xs text-gray-400 mt-1">{{ $panel->examination->name ?? '—' }}</p>
        <div class="mt-3 grid grid-cols-2 gap-2">
            <div class="text-center bg-gray-50 rounded-lg py-2"><p class="text-sm font-bold text-gray-900">{{ $panel->assignments_count }}</p><p class="text-[10px] text-gray-400">Assignments</p></div>
            <div class="text-center bg-gray-50 rounded-lg py-2"><p class="text-sm font-bold text-gray-900">{{ $panel->markers_count }}</p><p class="text-[10px] text-gray-400">Markers</p></div>
        </div>
    </a>
    @empty
    <div class="col-span-full bg-white rounded-xl border border-gray-100 p-8 text-center"><p class="text-sm text-gray-400">No panels assigned to you.</p></div>
    @endforelse
</div>
@endsection
