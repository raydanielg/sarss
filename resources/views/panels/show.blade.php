@extends('layouts.dashboard')
@section('title', $panel->subject->name . ' Panel - ' . config('app.name'))
@section('page_title', $panel->subject->name . ' Panel')

@section('content')
<div class="mb-6"><a href="{{ route('panels.index') }}" class="text-xs text-gray-400 hover:text-gray-600">&larr; Back to Panels</a></div>

<div class="bg-white rounded-xl border border-gray-100 p-5 mb-4">
    <div class="flex items-start justify-between">
        <div>
            <h2 class="text-lg font-bold text-gray-900">{{ $panel->subject->name ?? '—' }}</h2>
            <p class="text-xs text-gray-400 mt-1">{{ $panel->examination->name ?? '—' }}</p>
            <p class="text-xs text-gray-400">Moderator: <span class="font-medium text-gray-600">{{ $panel->moderator->name ?? '—' }}</span></p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    {{-- Markers --}}
    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-900">Markers</h3>
            <button onclick="document.getElementById('modal-marker').classList.toggle('hidden')" class="text-xs text-emerald-600 font-medium hover:text-emerald-700">+ Add Marker</button>
        </div>
        <div class="space-y-2">
            @forelse($panel->markers as $marker)
            <div class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-50">
                <div><p class="text-xs font-semibold text-gray-700">{{ $marker->name }}</p><p class="text-[10px] text-gray-400">{{ $marker->phone ?? '—' }} · {{ $marker->school->name ?? '—' }}</p></div>
                <form action="{{ route('panels.markers.destroy', [$panel, $marker]) }}" method="POST" class="inline" onsubmit="return confirm('Remove this marker?')">@csrf @method('DELETE')<button class="text-red-400 hover:text-red-600 text-xs">Remove</button></form>
            </div>
            @empty
            <p class="text-xs text-gray-400 text-center py-4">No markers yet.</p>
            @endforelse
        </div>
    </div>

    {{-- Data Entry Officers --}}
    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-900">Data Entry Officers</h3>
            <button onclick="document.getElementById('modal-de').classList.toggle('hidden')" class="text-xs text-emerald-600 font-medium hover:text-emerald-700">+ Add Officer</button>
        </div>
        <div class="space-y-2">
            @forelse($panel->dataEntries as $de)
            <div class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-50">
                <div><p class="text-xs font-semibold text-gray-700">{{ $de->user->name }}</p><p class="text-[10px] text-gray-400">{{ $de->user->email }}</p></div>
                <form action="{{ route('panels.data-entries.destroy', [$panel, $de]) }}" method="POST" class="inline" onsubmit="return confirm('Remove this officer?')">@csrf @method('DELETE')<button class="text-red-400 hover:text-red-600 text-xs">Remove</button></form>
            </div>
            @empty
            <p class="text-xs text-gray-400 text-center py-4">No data entry officers yet.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Assignments --}}
<div class="bg-white rounded-xl border border-gray-100 p-5 mt-4">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-sm font-semibold text-gray-900">Assignments</h3>
        <a href="{{ route('assignments.create') }}" class="text-xs text-emerald-600 font-medium hover:text-emerald-700">+ Create Assignment</a>
    </div>
    <div class="space-y-2">
        @forelse($panel->assignments as $assignment)
        <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50">
            <div><p class="text-xs font-semibold text-gray-700">{{ $assignment->user->name }}</p><p class="text-[10px] text-gray-400">{{ $assignment->district->name }} · {{ $assignment->schools->pluck('name')->implode(', ') }}</p></div>
        </div>
        @empty
        <p class="text-xs text-gray-400 text-center py-4">No assignments yet.</p>
        @endforelse
    </div>
</div>

{{-- Add Marker Modal --}}
<div id="modal-marker" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Add Marker</h3>
        <form action="{{ route('panels.markers.store', $panel) }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">Name</label><input type="text" name="name" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm outline-none"></div>
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">Phone</label><input type="text" name="phone" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm outline-none"></div>
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">School</label><select name="school_id" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm outline-none"><option value="">—</option>@foreach($schools as $school)<option value="{{ $school->id }}">{{ $school->name }}</option>@endforeach</select></div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="document.getElementById('modal-marker').classList.toggle('hidden')" class="flex-1 px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-600">Cancel</button>
                <button type="submit" class="flex-1 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-semibold">Add</button>
            </div>
        </form>
    </div>
</div>

{{-- Add Data Entry Modal --}}
<div id="modal-de" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-2">Add Data Entry Officer</h3>
        <p class="text-xs text-gray-400 mb-4">A user account will be created with a generated password.</p>
        <form action="{{ route('panels.data-entries.store', $panel) }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">Name</label><input type="text" name="name" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm outline-none"></div>
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">Email</label><input type="email" name="email" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm outline-none"></div>
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">Phone</label><input type="text" name="phone" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm outline-none"></div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="document.getElementById('modal-de').classList.toggle('hidden')" class="flex-1 px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-600">Cancel</button>
                <button type="submit" class="flex-1 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-semibold">Add & Generate Account</button>
            </div>
        </form>
    </div>
</div>
@endsection
