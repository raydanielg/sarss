@extends('layouts.dashboard')
@section('title', 'Panels - ' . config('app.name'))
@section('page_title', 'Panel Management')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <p class="text-sm text-gray-500">Manage marking panels for each subject.</p>
    <a href="{{ route('panels.create') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg transition-colors inline-flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Create Panel
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($panels as $panel)
    <div class="bg-white rounded-xl border border-gray-100 p-5 hover:shadow-lg transition-shadow">
        <div class="flex items-start justify-between mb-3">
            <div>
                <h3 class="text-sm font-bold text-gray-900">{{ $panel->subject->name ?? '—' }}</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ $panel->examination->name ?? '—' }}</p>
            </div>
        </div>
        <div class="grid grid-cols-3 gap-2 mb-4">
            <div class="text-center bg-gray-50 rounded-lg py-2"><p class="text-lg font-bold text-gray-900">{{ $panel->markers_count }}</p><p class="text-[10px] text-gray-400">Markers</p></div>
            <div class="text-center bg-gray-50 rounded-lg py-2"><p class="text-lg font-bold text-gray-900">{{ $panel->data_entries_count }}</p><p class="text-[10px] text-gray-400">Data Entry</p></div>
            <div class="text-center bg-gray-50 rounded-lg py-2"><p class="text-lg font-bold text-gray-900">{{ $panel->assignments_count }}</p><p class="text-[10px] text-gray-400">Assignments</p></div>
        </div>
        <p class="text-xs text-gray-400 mb-3">Moderator: <span class="font-medium text-gray-600">{{ $panel->moderator->name ?? '—' }}</span></p>
        <div class="flex items-center gap-2">
            <a href="{{ route('panels.show', $panel) }}" class="flex-1 text-center px-3 py-1.5 bg-emerald-50 text-emerald-700 rounded-lg text-xs font-medium hover:bg-emerald-100">Manage</a>
            <form action="{{ route('panels.destroy', $panel) }}" method="POST" class="inline" onsubmit="return confirm('Delete this panel?')">@csrf @method('DELETE')<button class="px-3 py-1.5 bg-red-50 text-red-600 rounded-lg text-xs font-medium hover:bg-red-100">Delete</button></form>
        </div>
    </div>
    @empty
    <div class="col-span-full bg-white rounded-xl border border-gray-100 p-8 text-center"><p class="text-sm text-gray-400">No panels yet.</p></div>
    @endforelse
</div>
@endsection
