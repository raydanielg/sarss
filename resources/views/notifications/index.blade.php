@extends('layouts.dashboard')
@section('title', 'Notifications - ' . config('app.name'))
@section('page_title', 'Notifications')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <p class="text-sm text-gray-500">Your system notifications.</p>
    <form action="{{ route('notifications.read-all') }}" method="POST">@csrf<button class="text-xs text-emerald-600 font-medium hover:text-emerald-700">Mark all as read</button></form>
</div>

<div class="space-y-2">
    @forelse($notifications as $notification)
    <div class="bg-white rounded-xl border border-gray-100 p-4 flex items-start gap-3 {{ $notification->read_at ? '' : 'border-l-4 border-l-emerald-500' }}">
        <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0
            @if($notification->type === 'success')bg-emerald-50 text-emerald-600
            @elseif($notification->type === 'error')bg-red-50 text-red-600
            @elseif($notification->type === 'warning')bg-amber-50 text-amber-600
            @else bg-sky-50 text-sky-600@endif">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5"/></svg>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-gray-900">{{ $notification->title }}</p>
            <p class="text-xs text-gray-500 mt-0.5">{{ $notification->message }}</p>
            <p class="text-[10px] text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
        </div>
        @if(!$notification->read_at)
        <form action="{{ route('notifications.read', $notification) }}" method="POST">@csrf<button class="text-xs text-emerald-600 hover:text-emerald-700 font-medium shrink-0">Mark read</button></form>
        @endif
    </div>
    @empty
    <div class="bg-white rounded-xl border border-gray-100 p-8 text-center"><p class="text-sm text-gray-400">No notifications.</p></div>
    @endforelse
</div>
{{ $notifications->links() }}
@endsection
