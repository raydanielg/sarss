@extends('layouts.dashboard')
@section('title', 'Assignments - ' . config('app.name'))
@section('page_title', 'Assignments')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <p class="text-sm text-gray-500">Manage work assignments for data entry officers.</p>
    <a href="{{ route('assignments.create') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg transition-colors inline-flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Create Assignment
    </a>
</div>

<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead><tr class="text-left text-xs text-gray-500 bg-gray-50/50 border-b">
            <th class="px-5 py-3 font-medium">Officer</th>
            <th class="px-5 py-3 font-medium">Subject</th>
            <th class="px-5 py-3 font-medium">District</th>
            <th class="px-5 py-3 font-medium">Schools</th>
            <th class="px-5 py-3 font-medium">Examination</th>
            <th class="px-5 py-3 font-medium text-right">Actions</th>
        </tr></thead>
        <tbody>
            @forelse($assignments as $assignment)
            <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors">
                <td class="px-5 py-3 font-semibold text-gray-900">{{ $assignment->user->name ?? '—' }}</td>
                <td class="px-5 py-3 text-gray-600">{{ $assignment->panel->subject->name ?? '—' }}</td>
                <td class="px-5 py-3 text-gray-600">{{ $assignment->district->name ?? '—' }}</td>
                <td class="px-5 py-3 text-gray-600 text-xs">{{ $assignment->schools->pluck('name')->implode(', ') }}</td>
                <td class="px-5 py-3 text-gray-600 text-xs">{{ $assignment->panel->examination->name ?? '—' }}</td>
                <td class="px-5 py-3 text-right">
                    <form action="{{ route('assignments.destroy', $assignment) }}" method="POST" class="inline" onsubmit="return confirm('Delete this assignment?')">@csrf @method('DELETE')<button class="text-red-500 hover:text-red-600 text-xs font-medium">Delete</button></form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-5 py-8 text-center text-gray-400">No assignments yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
