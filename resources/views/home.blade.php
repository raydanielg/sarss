@extends('layouts.dashboard')
@section('title', 'Dashboard - ' . config('app.name'))
@section('page_title', 'Dashboard')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Hello, {{ Auth::user()->name }}</h2>
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

{{-- KPI Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @php
        $cards = [];
        if(auth()->user()->hasAnyRole(['super_admin','exam_admin','viewer'])) {
            $cards = [
                ['label'=>'Examinations','value'=>$stats['examinations'] ?? 0,'sub'=>'Total created','color'=>'emerald','bg'=>'bg-emerald-50','text'=>'text-emerald-600'],
                ['label'=>'Active Exams','value'=>$stats['active_exams'] ?? 0,'sub'=>'Currently open','color'=>'amber','bg'=>'bg-amber-50','text'=>'text-amber-600'],
                ['label'=>'Candidates','value'=>$stats['candidates'] ?? 0,'sub'=>'Registered','color'=>'sky','bg'=>'bg-sky-50','text'=>'text-sky-600'],
                ['label'=>'Total Users','value'=>$stats['users'] ?? 0,'sub'=>'System accounts','color'=>'violet','bg'=>'bg-violet-50','text'=>'text-violet-600'],
            ];
        } elseif(auth()->user()->hasRole('moderator')) {
            $cards = [
                ['label'=>'My Panels','value'=>$stats['panels'] ?? 0,'sub'=>'Subject panels','color'=>'emerald','bg'=>'bg-emerald-50','text'=>'text-emerald-600'],
                ['label'=>'Pending Review','value'=>$stats['marks_pending'] ?? 0,'sub'=>'Awaiting action','color'=>'amber','bg'=>'bg-amber-50','text'=>'text-amber-600'],
                ['label'=>'Verified','value'=>$stats['marks_verified'] ?? 0,'sub'=>'Approved marks','color'=>'sky','bg'=>'bg-sky-50','text'=>'text-sky-600'],
                ['label'=>'Rejected','value'=>$stats['marks_rejected'] ?? 0,'sub'=>'Needs re-entry','color'=>'red','bg'=>'bg-red-50','text'=>'text-red-600'],
            ];
        } elseif(auth()->user()->hasRole('data_entry')) {
            $cards = [
                ['label'=>'Assignments','value'=>$stats['assignments'] ?? 0,'sub'=>'Allocated to you','color'=>'emerald','bg'=>'bg-emerald-50','text'=>'text-emerald-600'],
                ['label'=>'Schools','value'=>$stats['schools_assigned'] ?? 0,'sub'=>'To enter marks','color'=>'amber','bg'=>'bg-amber-50','text'=>'text-amber-600'],
                ['label'=>'Marks Entered','value'=>$stats['marks_entered'] ?? 0,'sub'=>'By you','color'=>'sky','bg'=>'bg-sky-50','text'=>'text-sky-600'],
                ['label'=>'Examinations','value'=>$stats['examinations'] ?? 0,'sub'=>'Active','color'=>'violet','bg'=>'bg-violet-50','text'=>'text-violet-600'],
            ];
        }
    @endphp

    @foreach($cards as $card)
    <div class="bg-white rounded-2xl border border-gray-100 p-5 hover:shadow-md transition-shadow">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-11 h-11 rounded-xl {{ $card['bg'] }} flex items-center justify-center shrink-0">
                @if($card['label'] === 'Examinations' || $card['label'] === 'Examinations')
                <svg class="w-5 h-5 {{ $card['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                @elseif($card['label'] === 'Active Exams')
                <svg class="w-5 h-5 {{ $card['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                @elseif($card['label'] === 'Candidates')
                <svg class="w-5 h-5 {{ $card['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                @elseif($card['label'] === 'Total Users')
                <svg class="w-5 h-5 {{ $card['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                @elseif($card['label'] === 'My Panels')
                <svg class="w-5 h-5 {{ $card['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                @elseif($card['label'] === 'Pending Review')
                <svg class="w-5 h-5 {{ $card['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                @elseif($card['label'] === 'Verified')
                <svg class="w-5 h-5 {{ $card['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                @elseif($card['label'] === 'Rejected')
                <svg class="w-5 h-5 {{ $card['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                @elseif($card['label'] === 'Assignments')
                <svg class="w-5 h-5 {{ $card['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                @elseif($card['label'] === 'Schools')
                <svg class="w-5 h-5 {{ $card['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-4a1 1 0 011-1h2a1 1 0 011 1v4"/></svg>
                @elseif($card['label'] === 'Marks Entered')
                <svg class="w-5 h-5 {{ $card['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                @else
                <svg class="w-5 h-5 {{ $card['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                @endif
            </div>
            <div class="min-w-0">
                <p class="text-[10px] font-medium text-gray-400 uppercase tracking-wide">{{ $card['label'] }}</p>
                <p class="text-2xl font-bold text-gray-900 leading-tight">{{ $card['value'] }}</p>
            </div>
        </div>
        <p class="text-[10px] text-gray-400">{{ $card['sub'] }}</p>
    </div>
    @endforeach
</div>

{{-- Charts Row --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
    {{-- Bar Chart: Marks by Subject --}}
    @if(auth()->user()->hasAnyRole(['super_admin','exam_admin','viewer']) && !empty($chartData['subject_labels']))
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-gray-900">Marks Progress by Subject</h3>
            <div class="flex items-center gap-3 text-[10px]">
                <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-sm bg-emerald-500"></span>Entered</span>
                <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-sm bg-gold-400"></span>Verified</span>
            </div>
        </div>
        <div style="height: 240px;"><canvas id="chart-subjects"></canvas></div>
    </div>
    @else
    {{-- Recent Examinations for non-admin --}}
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 p-5">
        <h3 class="text-sm font-bold text-gray-900 mb-4">Recent Examinations</h3>
        <div class="space-y-2">
            @forelse($recentExams as $exam)
            <a href="{{ route('examinations.show', $exam) }}" class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-50 transition-colors">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-emerald-50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div><p class="text-sm font-semibold text-gray-900">{{ $exam->name }}</p><p class="text-[10px] text-gray-400">{{ $exam->academicYear->year ?? '' }} · {{ $exam->examType->name ?? '' }}</p></div>
                </div>
                @php $statusColors = ['draft'=>'gray','open'=>'emerald','closed'=>'amber','archived'=>'red']; @endphp
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-{{ $statusColors[$exam->status] ?? 'gray' }}-50 text-{{ $statusColors[$exam->status] ?? 'gray' }}-700 border border-{{ $statusColors[$exam->status] ?? 'gray' }}-100 capitalize">{{ $exam->status }}</span>
            </a>
            @empty
            <p class="text-xs text-gray-400 text-center py-4">No examinations yet.</p>
            @endforelse
        </div>
    </div>
    @end

    {{-- Circular Progress + Donut --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-5">
        <h3 class="text-sm font-bold text-gray-900 mb-4">Overall Progress</h3>

        {{-- Circular Progress: Entry Rate --}}
        @php $entryPct = $chartData['entry_pct'] ?? 0; @endphp
        <div class="flex items-center gap-4 mb-5">
            <div class="relative w-20 h-20 shrink-0">
                <svg class="w-20 h-20 transform -rotate-90" viewBox="0 0 80 80">
                    <circle cx="40" cy="40" r="34" stroke="#f3f4f6" stroke-width="6" fill="none"/>
                    <circle cx="40" cy="40" r="34" stroke="#10b981" stroke-width="6" fill="none" stroke-linecap="round"
                        stroke-dasharray="{{ 2 * pi() * 34 }}" stroke-dashoffset="{{ 2 * pi() * 34 * (1 - $entryPct / 100) }}"
                        style="transition: stroke-dashoffset 1s ease;"/>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-base font-bold text-gray-900">{{ $entryPct }}<span class="text-[10px] text-gray-400">%</span></span>
                </div>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-700">Marks Entry</p>
                <p class="text-[10px] text-gray-400">{{ $chartData['entered_marks'] ?? 0 }} entered</p>
                @if(isset($chartData['total_marks']))<p class="text-[10px] text-gray-400">of {{ $chartData['total_marks'] }} total</p>@endif
            </div>
        </div>

        {{-- Circular Progress: Verification Rate --}}
        @php $veriPct = $chartData['verification_pct'] ?? 0; @endphp
        <div class="flex items-center gap-4 mb-5">
            <div class="relative w-20 h-20 shrink-0">
                <svg class="w-20 h-20 transform -rotate-90" viewBox="0 0 80 80">
                    <circle cx="40" cy="40" r="34" stroke="#f3f4f6" stroke-width="6" fill="none"/>
                    <circle cx="40" cy="40" r="34" stroke="#f9ac00" stroke-width="6" fill="none" stroke-linecap="round"
                        stroke-dasharray="{{ 2 * pi() * 34 }}" stroke-dashoffset="{{ 2 * pi() * 34 * (1 - $veriPct / 100) }}"
                        style="transition: stroke-dashoffset 1s ease;"/>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-base font-bold text-gray-900">{{ $veriPct }}<span class="text-[10px] text-gray-400">%</span></span>
                </div>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-700">Verification</p>
                <p class="text-[10px] text-gray-400">{{ $chartData['verified_marks'] ?? 0 }} verified</p>
                @if(isset($chartData['entered_marks']))<p class="text-[10px] text-gray-400">of {{ $chartData['entered_marks'] }} entered</p>@endif
            </div>
        </div>

        {{-- Exam Status Donut (admin only) --}}
        @if(auth()->user()->hasAnyRole(['super_admin','exam_admin','viewer']) && !empty($chartData['exam_statuses']))
        <div class="border-t border-gray-50 pt-4">
            <p class="text-[10px] font-medium text-gray-400 uppercase tracking-wide mb-3">Exam Status</p>
            <div class="flex items-center gap-4">
                <div style="width: 100px; height: 100px;"><canvas id="chart-donut"></canvas></div>
                <div class="space-y-1.5 text-[10px]">
                    @foreach($chartData['exam_statuses'] as $status => $count)
                    @php $donutColors = ['draft'=>'#9ca3af','open'=>'#10b981','closed'=>'#f59e0b','archived'=>'#ef4444']; @endphp
                    <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full" style="background: {{ $donutColors[$status] }}"></span><span class="text-gray-600 capitalize">{{ $status }}</span><span class="font-bold text-gray-900">{{ $count }}</span></div>
                    @endforeach
                </div>
            </div>
        </div>
        @end
    </div>
</div>

{{-- Recent Examinations + Login Activities --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
    {{-- Recent Examinations (admin/viewer) --}}
    @if(auth()->user()->hasAnyRole(['super_admin','exam_admin','viewer']))
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-gray-900">Recent Examinations</h3>
            <a href="{{ route('examinations.index') }}" class="text-[10px] text-emerald-600 font-medium hover:text-emerald-700">View all</a>
        </div>
        <div class="space-y-2">
            @forelse($recentExams as $exam)
            <a href="{{ route('examinations.show', $exam) }}" class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-50 transition-colors">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-emerald-50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div><p class="text-sm font-semibold text-gray-900">{{ $exam->name }}</p><p class="text-[10px] text-gray-400">{{ $exam->academicYear->year ?? '' }} · {{ $exam->examType->name ?? '' }}</p></div>
                </div>
                @php $statusColors = ['draft'=>'gray','open'=>'emerald','closed'=>'amber','archived'=>'red']; @endphp
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-{{ $statusColors[$exam->status] ?? 'gray' }}-50 text-{{ $statusColors[$exam->status] ?? 'gray' }}-700 border border-{{ $statusColors[$exam->status] ?? 'gray' }}-100 capitalize">{{ $exam->status }}</span>
            </a>
            @empty
            <p class="text-xs text-gray-400 text-center py-4">No examinations yet.</p>
            @endforelse
        </div>
    </div>
    @end

    {{-- Login Activities --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-5">
        <div class="flex items-center gap-2 mb-4">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            <h3 class="text-sm font-bold text-gray-900">Login Activities</h3>
        </div>
        <div class="space-y-3">
            @forelse($loginActivities as $log)
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white font-bold text-[10px] shrink-0">
                    {{ strtoupper(substr($log->user->name ?? 'S', 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-semibold text-gray-900 truncate">{{ $log->user->name ?? 'System' }}</p>
                    <p class="text-[10px] text-gray-400">{{ $log->created_at->diffForHumans() }}</p>
                    @if($log->ip_address)<p class="text-[9px] text-gray-300 font-mono mt-0.5">{{ $log->ip_address }}</p>@end
                </div>
                <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-medium bg-emerald-50 text-emerald-600 border border-emerald-100 shrink-0">Login</span>
            </div>
            @empty
            <div class="text-center py-6">
                <svg class="w-8 h-8 text-gray-200 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                <p class="text-[10px] text-gray-400">No login activity yet.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Notifications + Quick Actions --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
    {{-- Notifications --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5"/></svg>
                <h3 class="text-sm font-bold text-gray-900">Notifications</h3>
            </div>
            <a href="{{ route('notifications.index') }}" class="text-[10px] text-emerald-600 font-medium hover:text-emerald-700">View all</a>
        </div>
        <div class="space-y-2">
            @forelse($notifications as $notification)
            <div class="flex items-start gap-3 p-2.5 rounded-xl bg-gray-50">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0
                    @if($notification->type === 'success')bg-emerald-50 text-emerald-600
                    @elseif($notification->type === 'error')bg-red-50 text-red-600
                    @elseif($notification->type === 'warning')bg-amber-50 text-amber-600
                    @else bg-sky-50 text-sky-600 @end">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5"/></svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-semibold text-gray-700">{{ $notification->title }}</p>
                    <p class="text-[10px] text-gray-400 mt-0.5 line-clamp-1">{{ $notification->message }}</p>
                    <p class="text-[9px] text-gray-300 mt-0.5">{{ $notification->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @empty
            <div class="text-center py-6">
                <svg class="w-8 h-8 text-gray-200 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5"/></svg>
                <p class="text-[10px] text-gray-400">No notifications.</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-5">
        <div class="flex items-center gap-2 mb-4">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            <h3 class="text-sm font-bold text-gray-900">Quick Actions</h3>
        </div>
        <div class="grid grid-cols-3 gap-3">
            @if(auth()->user()->hasAnyRole(['super_admin','exam_admin']))
            <a href="{{ route('examinations.create') }}" class="flex flex-col items-center gap-2 p-4 rounded-2xl bg-emerald-50 hover:bg-emerald-100 transition-colors group">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 group-hover:bg-emerald-200 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-[10px] font-medium text-emerald-700 text-center">New Exam</span>
            </a>
            <a href="{{ route('candidates.index') }}" class="flex flex-col items-center gap-2 p-4 rounded-2xl bg-amber-50 hover:bg-amber-100 transition-colors group">
                <div class="w-10 h-10 rounded-xl bg-amber-100 group-hover:bg-amber-200 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <span class="text-[10px] font-medium text-amber-700 text-center">Candidates</span>
            </a>
            @end
            @if(auth()->user()->hasAnyRole(['super_admin','exam_admin','moderator']))
            <a href="{{ route('panels.index') }}" class="flex flex-col items-center gap-2 p-4 rounded-2xl bg-sky-50 hover:bg-sky-100 transition-colors group">
                <div class="w-10 h-10 rounded-xl bg-sky-100 group-hover:bg-sky-200 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <span class="text-[10px] font-medium text-sky-700 text-center">Panels</span>
            </a>
            @end
            @if(auth()->user()->hasRole('data_entry'))
            <a href="{{ route('marks.entry') }}" class="flex flex-col items-center gap-2 p-4 rounded-2xl bg-emerald-50 hover:bg-emerald-100 transition-colors group">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 group-hover:bg-emerald-200 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <span class="text-[10px] font-medium text-emerald-700 text-center">Enter Marks</span>
            </a>
            @end
            @if(auth()->user()->hasRole('moderator'))
            <a href="{{ route('verification.index') }}" class="flex flex-col items-center gap-2 p-4 rounded-2xl bg-violet-50 hover:bg-violet-100 transition-colors group">
                <div class="w-10 h-10 rounded-xl bg-violet-100 group-hover:bg-violet-200 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-[10px] font-medium text-violet-700 text-center">Verify</span>
            </a>
            @end
            @if(auth()->user()->hasAnyRole(['super_admin','exam_admin','moderator','viewer']))
            <a href="{{ route('reports.index') }}" class="flex flex-col items-center gap-2 p-4 rounded-2xl bg-gray-50 hover:bg-gray-100 transition-colors group">
                <div class="w-10 h-10 rounded-xl bg-gray-100 group-hover:bg-gray-200 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <span class="text-[10px] font-medium text-gray-700 text-center">Reports</span>
            </a>
            @end
            @if(auth()->user()->hasRole('super_admin'))
            <a href="{{ route('users.index') }}" class="flex flex-col items-center gap-2 p-4 rounded-2xl bg-rose-50 hover:bg-rose-100 transition-colors group">
                <div class="w-10 h-10 rounded-xl bg-rose-100 group-hover:bg-rose-200 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <span class="text-[10px] font-medium text-rose-700 text-center">Users</span>
            </a>
            @end
        </div>
    </div>
</div>

@push('scripts')
<script>
@if(auth()->user()->hasAnyRole(['super_admin','exam_admin','viewer']) && !empty($chartData['subject_labels']))
new Chart(document.getElementById('chart-subjects'), {
    type: 'bar',
    data: {
        labels: @json($chartData['subject_labels']),
        datasets: [
            {
                label: 'Entered',
                data: @json($chartData['subject_entered']),
                backgroundColor: '#10b981',
                borderRadius: 6,
                barThickness: 18,
            },
            {
                label: 'Verified',
                data: @json($chartData['subject_verified']),
                backgroundColor: '#f9ac00',
                borderRadius: 6,
                barThickness: 18,
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { display: false }, ticks: { font: { size: 10 }, color: '#9ca3af' } },
            y: { grid: { color: '#f3f4f6' }, ticks: { font: { size: 10 }, color: '#9ca3af' }, beginAtZero: true }
        }
    }
});

new Chart(document.getElementById('chart-donut'), {
    type: 'doughnut',
    data: {
        labels: ['Draft', 'Open', 'Closed', 'Archived'],
        datasets: [{
            data: [
                {{ $chartData['exam_statuses']['draft'] ?? 0 }},
                {{ $chartData['exam_statuses']['open'] ?? 0 }},
                {{ $chartData['exam_statuses']['closed'] ?? 0 }},
                {{ $chartData['exam_statuses']['archived'] ?? 0 }}
            ],
            backgroundColor: ['#9ca3af', '#10b981', '#f59e0b', '#ef4444'],
            borderWidth: 0,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '65%',
        plugins: { legend: { display: false } }
    }
});
@end
</script>
@endpush
@endsection
