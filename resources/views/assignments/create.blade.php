@extends('layouts.dashboard')
@section('title', 'Create Assignment - ' . config('app.name'))
@section('page_title', 'Create Assignment')

@section('content')
<div class="mb-6"><a href="{{ route('assignments.index') }}" class="text-xs text-gray-400 hover:text-gray-600">&larr; Back to Assignments</a></div>

<form action="{{ route('assignments.store') }}" method="POST">
    @csrf
    <div class="bg-white rounded-xl border border-gray-100 p-6 max-w-2xl">
        <h3 class="text-sm font-bold text-gray-900 mb-4">Assignment Details</h3>
        <div class="space-y-4">
            <div><label class="block text-xs font-semibold text-gray-600 mb-1">Panel (Subject)</label><select name="panel_id" id="panel-select" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm outline-none"><option value="">Select...</option>@foreach($panels as $panel)<option value="{{ $panel->id }}">{{ $panel->subject->name }} - {{ $panel->examination->name }}</option>@endforeach</select></div>
            <div><label class="block text-xs font-semibold text-gray-600 mb-1">Data Entry Officer</label><select name="user_id" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm outline-none"><option value="">Select...</option>@foreach(\App\Models\User::where('role','data_entry')->orderBy('name')->get() as $user)<option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>@endforeach</select></div>
            <div><label class="block text-xs font-semibold text-gray-600 mb-1">District</label><select name="district_id" id="district-select" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm outline-none"><option value="">Select...</option>@foreach($districts as $district)<option value="{{ $district->id }}">{{ $district->name }}</option>@endforeach</select></div>
            <div><label class="block text-xs font-semibold text-gray-600 mb-1">Schools</label><div class="grid grid-cols-2 md:grid-cols-3 gap-2 max-h-48 overflow-y-auto border border-gray-100 rounded-lg p-3">@foreach($districts->flatMap->schools as $school)<label class="flex items-center gap-2 text-xs text-gray-600"><input type="checkbox" name="schools[]" value="{{ $school->id }}" class="rounded border-gray-300 text-emerald-600">{{ $school->name }}</label>@endforeach</div></div>
        </div>
        <div class="flex gap-3 mt-6">
            <a href="{{ route('assignments.index') }}" class="px-6 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50">Cancel</a>
            <button type="submit" class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-semibold">Create Assignment</button>
        </div>
    </div>
</form>
@endsection
