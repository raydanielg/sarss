@extends('layouts.dashboard')
@section('title', 'Create Examination - ' . config('app.name'))
@section('page_title', 'Create Examination')

@section('content')
<div class="mb-6">
    <a href="{{ route('examinations.index') }}" class="text-xs text-gray-400 hover:text-gray-600 inline-flex items-center gap-1">&larr; Back to Examinations</a>
</div>

<form action="{{ route('examinations.store') }}" method="POST">
    @csrf
    <div class="bg-white rounded-xl border border-gray-100 p-6 mb-4">
        <h3 class="text-sm font-bold text-gray-900 mb-4">Basic Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><label class="block text-xs font-semibold text-gray-600 mb-1">Examination Name</label><input type="text" name="name" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none" placeholder="Mock Examination 2026"></div>
            <div><label class="block text-xs font-semibold text-gray-600 mb-1">Academic Year</label><select name="academic_year_id" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none"><option value="">Select...</option>@foreach($academicYears as $year)<option value="{{ $year->id }}">{{ $year->year }}</option>@endforeach</select></div>
            <div><label class="block text-xs font-semibold text-gray-600 mb-1">Exam Type</label><select name="exam_type_id" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none"><option value="">Select...</option>@foreach($examTypes as $type)<option value="{{ $type->id }}">{{ $type->name }}</option>@endforeach</select></div>
            <div><label class="block text-xs font-semibold text-gray-600 mb-1">Region</label><select name="region_id" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none"><option value="">Select...</option>@foreach($regions as $region)<option value="{{ $region->id }}">{{ $region->name }}</option>@endforeach</select></div>
            <div><label class="block text-xs font-semibold text-gray-600 mb-1">Start Date</label><input type="date" name="start_date" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none"></div>
            <div><label class="block text-xs font-semibold text-gray-600 mb-1">End Date</label><input type="date" name="end_date" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none"></div>
        </div>
        <div class="mt-4"><label class="block text-xs font-semibold text-gray-600 mb-1">Description</label><textarea name="description" rows="2" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none"></textarea></div>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 p-6 mb-4">
        <h3 class="text-sm font-bold text-gray-900 mb-4">Districts</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
            @foreach($districts as $district)<label class="flex items-center gap-2 text-xs text-gray-600"><input type="checkbox" name="districts[]" value="{{ $district->id }}" class="rounded border-gray-300 text-emerald-600">{{ $district->name }}</label>@endforeach
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 p-6 mb-4">
        <h3 class="text-sm font-bold text-gray-900 mb-4">Schools</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-2 max-h-48 overflow-y-auto">
            @foreach($schools as $school)<label class="flex items-center gap-2 text-xs text-gray-600"><input type="checkbox" name="schools[]" value="{{ $school->id }}" class="rounded border-gray-300 text-emerald-600">{{ $school->name }}</label>@endforeach
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 p-6 mb-4">
        <h3 class="text-sm font-bold text-gray-900 mb-4">Subjects</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
            @foreach($subjects as $subject)<label class="flex items-center gap-2 text-xs text-gray-600"><input type="checkbox" name="subjects[]" value="{{ $subject->id }}" class="rounded border-gray-300 text-emerald-600">{{ $subject->name }}</label>@endforeach
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 p-6 mb-4">
        <h3 class="text-sm font-bold text-gray-900 mb-4">Classes</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
            @foreach($classes as $class)<label class="flex items-center gap-2 text-xs text-gray-600"><input type="checkbox" name="classes[]" value="{{ $class->id }}" class="rounded border-gray-300 text-emerald-600">{{ $class->name }}</label>@endforeach
        </div>
    </div>

    <div class="flex gap-3">
        <a href="{{ route('examinations.index') }}" class="px-6 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50">Cancel</a>
        <button type="submit" class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-semibold">Create Examination</button>
    </div>
</form>
@endsection
