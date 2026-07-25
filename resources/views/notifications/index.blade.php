@extends('layouts.dashboard')
@section('title', 'Notifications - ' . config('app.name'))
@section('page_title', 'Notifications')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <p class="text-sm text-gray-500">Your system notifications.</p>
    <form action="{{ route('notifications.read-all') }}" method="POST">@csrf<button class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-lg text-xs font-semibold transition-colors inline-flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Mark all as read</button></form>
</div>

@php
    $typeStyles = [
        'success' => ['bg'=>'bg-emerald-50','text'=>'text-emerald-600','icon'=>'M5 13l4 4L19 7'],
        'error'   => ['bg'=>'bg-red-50','text'=>'text-red-600','icon'=>'M6 18L18 6M6 6l12 12'],
        'warning' => ['bg'=>'bg-amber-50','text'=>'text-amber-600','icon'=>'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
        'info'    => ['bg'=>'bg-sky-50','text'=>'text-sky-600','icon'=>'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
    ];
@endphp

<div class="space-y-2">
    @forelse($notifications as $notification)
    @php $ts = $typeStyles[$notification->type] ?? $typeStyles['info']; @endphp
    <div class="bg-white rounded-2xl border border-gray-100 p-4 flex items-start gap-3 shadow-sm transition-all hover:shadow-md {{ $notification->read_at ? '' : 'border-l-4 border-l-emerald-500' }}">
        <div class="w-9 h-9 rounded-xl {{ $ts['bg'] }} {{ $ts['text'] }} flex items-center justify-center shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $ts['icon'] }}"/></svg>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-gray-900">{{ $notification->title }}</p>
            <p class="text-xs text-gray-500 mt-0.5">{{ $notification->message }}</p>
            <p class="text-[10px] text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
        </div>
        @if(!$notification->read_at)
        <form action="{{ route('notifications.read', $notification) }}" method="POST">@csrf<button class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-lg text-xs font-semibold transition-colors shrink-0">Mark read</button></form>
        @endif
    </div>
    @empty
    <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center shadow-sm">
        <svg class="w-14 h-14 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5"/></svg>
        <p class="text-sm text-gray-400">No notifications.</p>
    </div>
    @endforelse
</div>
<div class="mt-4">{{ $notifications->links() }}</div>
@endsection
