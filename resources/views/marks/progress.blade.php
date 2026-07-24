@extends('layouts.dashboard')
@section('title', 'My Progress - ' . config('app.name'))
@section('page_title', 'My Progress')

@section('content')
<div class="mb-6"><p class="text-sm text-gray-500">Track your marks entry progress across all assignments.</p></div>

<div class="space-y-4">
    @forelse($progress as $item)
    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-3">
            <div>
                <h3 class="text-sm font-bold text-gray-900">{{ $item['school']->name }}</h3>
                <p class="text-xs text-gray-400">{{ $item['assignment']->panel->subject->name ?? '—' }} · {{ $item['assignment']->district->name ?? '—' }}</p>
            </div>
            <div class="text-right">
                <p class="text-lg font-bold text-emerald-600">{{ $item['percentage'] }}%</p>
                <p class="text-[10px] text-gray-400">{{ $item['entered'] }} / {{ $item['total'] }}</p>
            </div>
        </div>
        <div class="w-full bg-gray-100 rounded-full h-2">
            <div class="bg-emerald-500 h-2 rounded-full transition-all" style="width: {{ $item['percentage'] }}%"></div>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-xl border border-gray-100 p-8 text-center"><p class="text-sm text-gray-400">No progress data yet.</p></div>
    @endforelse
</div>
@endsection
