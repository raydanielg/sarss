@extends('layouts.dashboard')
@section('title', 'Create Panel - ' . config('app.name'))
@section('page_title', 'Create Panel')

@section('content')
<div class="mb-6"><a href="{{ route('panels.index') }}" class="text-xs text-gray-400 hover:text-gray-600">&larr; Back to Panels</a></div>

<form action="{{ route('panels.store') }}" method="POST">
    @csrf
    <div class="bg-white rounded-xl border border-gray-100 p-6 max-w-lg">
        <h3 class="text-sm font-bold text-gray-900 mb-4">Panel Details</h3>
        <div class="space-y-4">
            <div><label class="block text-xs font-semibold text-gray-600 mb-1">Examination</label><select name="examination_id" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm outline-none"><option value="">Select...</option>@foreach($examinations as $exam)<option value="{{ $exam->id }}">{{ $exam->name }}</option>@endforeach</select></div>
            <div><label class="block text-xs font-semibold text-gray-600 mb-1">Subject</label><select name="subject_id" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm outline-none"><option value="">Select...</option>@foreach($subjects as $subject)<option value="{{ $subject->id }}">{{ $subject->name }}</option>@endforeach</select></div>
            <div><label class="block text-xs font-semibold text-gray-600 mb-1">Moderator</label><select name="moderator_user_id" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm outline-none"><option value="">Select...</option>@foreach($moderators as $mod)<option value="{{ $mod->id }}">{{ $mod->name }} ({{ $mod->email }})</option>@endforeach</select></div>
        </div>
        <div class="flex gap-3 mt-6">
            <a href="{{ route('panels.index') }}" class="px-6 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50">Cancel</a>
            <button type="submit" class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-semibold">Create Panel</button>
        </div>
    </div>
</form>
@endsection
