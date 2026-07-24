@extends('layouts.dashboard')
@section('title', 'Dashboard - ' . config('app.name'))
@section('page_title', 'Dashboard')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Hello, {{ Auth::user()->name }} 👋</h2>
            <p class="text-xs text-gray-400 mt-1">Welcome back to {{ config('app.name') }}. Here's your overview.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full text-xs font-medium border border-emerald-100 capitalize">{{ str_replace('_', ' ', Auth::user()->role) }}</span>
        </div>
    </div>
</div>

@if(session('status'))
<div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-100 rounded-lg text-sm text-emerald-700">{{ session('status') }}</div>
@endif

{{-- Stats Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
    @php
        $cards = [];
        if(auth()->user()->hasAnyRole(['super_admin','exam_admin','viewer'])) {
            $cards = [
                ['label'=>'Examinations','value'=>$stats['examinations'] ?? 0,'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2','color'=>'emerald'],
                ['label'=>'Active Exams','value'=>$stats['active_exams'] ?? 0,'icon'=>'M13 10V3L4 14h7v7l9-11h-7z','color'=>'amber'],
                ['label'=>'Candidates','value'=>$stats['candidates'] ?? 0,'icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7','color'=>'sky'],
                ['label'=>'Total Users','value'=>$stats['users'] ?? 0,'icon'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z','color'=>'violet'],
            ];
        } elseif(auth()->user()->hasRole('moderator')) {
            $cards = [
                ['label'=>'My Panels','value'=>$stats['panels'] ?? 0,'icon'=>'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5','color'=>'emerald'],
                ['label'=>'Pending Review','value'=>$stats['marks_pending'] ?? 0,'icon'=>'M12 8v4l3 3','color'=>'amber'],
                ['label'=>'Verified','value'=>$stats['marks_verified'] ?? 0,'icon'=>'M9 12l2 2 4-4','color'=>'sky'],
                ['label'=>'Rejected','value'=>$stats['marks_rejected'] ?? 0,'icon'=>'M6 18L18 6','color'=>'red'],
            ];
        } elseif(auth()->user()->hasRole('data_entry')) {
            $cards = [
                ['label'=>'Assignments','value'=>$stats['assignments'] ?? 0,'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7','color'=>'emerald'],
                ['label'=>'Schools','value'=>$stats['schools_assigned'] ?? 0,'icon'=>'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16','color'=>'amber'],
                ['label'=>'Marks Entered','value'=>$stats['marks_entered'] ?? 0,'icon'=>'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5','color'=>'sky'],
                ['label'=>'Examinations','value'=>$stats['examinations'] ?? 0,'icon'=>'M13 10V3L4 14h7v7l9-11h-7z','color'=>'violet'],
            ];
        }
    @endphp

    @foreach($cards as $card)
    <div class="bg-gradient-to-br from-{{ $card['color'] }}-500 to-{{ $card['color'] }}-700 rounded-xl p-4 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -mr-10 -mt-10"></div>
        <div class="relative z-10">
            <div class="flex items-start justify-between mb-2">
                <span class="text-[10px] font-medium text-white/80">{{ $card['label'] }}</span>
                <svg class="w-4 h-4 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/></svg>
            </div>
            <p class="text-2xl font-bold">{{ $card['value'] }}</p>
        </div>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    {{-- Recent Examinations --}}
    <div class="lg:col-span-2 bg-white rounded-xl border border-gray-100 p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Recent Examinations</h3>
        <div class="space-y-2">
            @forelse($recentExams as $exam)
            <a href="{{ route('examinations.show', $exam) }}" class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors">
                <div><p class="text-sm font-semibold text-gray-900">{{ $exam->name }}</p><p class="text-[10px] text-gray-400">{{ $exam->academicYear->year ?? '' }} · {{ $exam->examType->name ?? '' }}</p></div>
                @php $statusColors = ['draft'=>'gray','open'=>'emerald','closed'=>'amber','archived'=>'red']; @endphp
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-{{ $statusColors[$exam->status] ?? 'gray' }}-50 text-{{ $statusColors[$exam->status] ?? 'gray' }}-700 border border-{{ $statusColors[$exam->status] ?? 'gray' }}-100 capitalize">{{ $exam->status }}</span>
            </a>
            @empty
            <p class="text-xs text-gray-400 text-center py-4">No examinations yet.</p>
            @endforelse
        </div>
    </div>

    {{-- Notifications --}}
    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-900">Recent Notifications</h3>
            <a href="{{ route('notifications.index') }}" class="text-[10px] text-emerald-600 font-medium hover:text-emerald-700">View all</a>
        </div>
        <div class="space-y-2">
            @forelse($notifications as $notification)
            <div class="p-2.5 rounded-lg bg-gray-50">
                <p class="text-xs font-semibold text-gray-700">{{ $notification->title }}</p>
                <p class="text-[10px] text-gray-400 mt-0.5 line-clamp-2">{{ $notification->message }}</p>
                <p class="text-[10px] text-gray-300 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
            </div>
            @empty
            <p class="text-xs text-gray-400 text-center py-4">No notifications.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="mt-6 bg-white rounded-xl border border-gray-100 p-5">
    <h3 class="text-sm font-semibold text-gray-900 mb-4">Quick Actions</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        @if(auth()->user()->hasAnyRole(['super_admin','exam_admin']))
        <a href="{{ route('examinations.create') }}" class="flex flex-col items-center gap-2 p-4 rounded-xl bg-emerald-50 hover:bg-emerald-100 transition-colors">
            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/></svg>
            <span class="text-xs font-medium text-emerald-700">New Exam</span>
        </a>
        <a href="{{ route('candidates.index') }}" class="flex flex-col items-center gap-2 p-4 rounded-xl bg-amber-50 hover:bg-amber-100 transition-colors">
            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857"/></svg>
            <span class="text-xs font-medium text-amber-700">Candidates</span>
        </a>
        @endif
        @if(auth()->user()->hasAnyRole(['super_admin','exam_admin','moderator']))
        <a href="{{ route('panels.index') }}" class="flex flex-col items-center gap-2 p-4 rounded-xl bg-sky-50 hover:bg-sky-100 transition-colors">
            <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5"/></svg>
            <span class="text-xs font-medium text-sky-700">Panels</span>
        </a>
        @endif
        @if(auth()->user()->hasRole('data_entry'))
        <a href="{{ route('marks.entry') }}" class="flex flex-col items-center gap-2 p-4 rounded-xl bg-emerald-50 hover:bg-emerald-100 transition-colors">
            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5"/></svg>
            <span class="text-xs font-medium text-emerald-700">Enter Marks</span>
        </a>
        @endif
        @if(auth()->user()->hasRole('moderator'))
        <a href="{{ route('verification.index') }}" class="flex flex-col items-center gap-2 p-4 rounded-xl bg-violet-50 hover:bg-violet-100 transition-colors">
            <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-xs font-medium text-violet-700">Verify Marks</span>
        </a>
        @endif
        @if(auth()->user()->hasAnyRole(['super_admin','exam_admin','moderator','viewer']))
        <a href="{{ route('reports.index') }}" class="flex flex-col items-center gap-2 p-4 rounded-xl bg-gray-50 hover:bg-gray-100 transition-colors">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2z"/></svg>
            <span class="text-xs font-medium text-gray-700">Reports</span>
        </a>
        @endif
    </div>
</div>
@endsection
