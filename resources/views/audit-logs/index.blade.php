@extends('layouts.dashboard')
@section('title', 'Audit Logs - ' . config('app.name'))
@section('page_title', 'Audit Logs')

@section('content')
<div class="mb-4">
    <p class="text-sm text-gray-500">Complete activity log of all system actions.</p>
</div>

<form method="GET" class="mb-4 flex gap-2 flex-wrap">
    <select name="module" class="px-3 py-2 border border-gray-200 rounded-lg text-sm outline-none">
        <option value="">All Modules</option>
        @foreach($modules as $module)<option value="{{ $module }}" @selected(request('module') === $module)>{{ $module }}</option>@endforeach
    </select>
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search action or description..." class="px-3 py-2 border border-gray-200 rounded-lg text-sm outline-none flex-1 min-w-[200px]">
    <button type="submit" class="px-4 py-2 bg-gray-50 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-100">Filter</button>
</form>

<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead><tr class="text-left text-xs text-gray-500 bg-gray-50/50 border-b">
            <th class="px-5 py-3 font-medium">Time</th>
            <th class="px-5 py-3 font-medium">User</th>
            <th class="px-5 py-3 font-medium">Action</th>
            <th class="px-5 py-3 font-medium">Module</th>
            <th class="px-5 py-3 font-medium">Description</th>
            <th class="px-5 py-3 font-medium">IP</th>
        </tr></thead>
        <tbody>
            @forelse($logs as $log)
            <tr class="border-b border-gray-50 hover:bg-gray-50/50">
                <td class="px-5 py-2.5 text-xs text-gray-400">{{ $log->created_at->format('d M Y H:i') }}</td>
                <td class="px-5 py-2.5 font-semibold text-gray-900 text-xs">{{ $log->user->name ?? 'System' }}</td>
                <td class="px-5 py-2.5"><span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-gray-50 text-gray-600 border border-gray-100">{{ $log->action }}</span></td>
                <td class="px-5 py-2.5 text-gray-600 text-xs">{{ $log->module }}</td>
                <td class="px-5 py-2.5 text-gray-600 text-xs">{{ $log->description ?? '—' }}</td>
                <td class="px-5 py-2.5 text-gray-400 text-xs font-mono">{{ $log->ip_address ?? '—' }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-5 py-8 text-center text-gray-400">No audit logs found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $logs->links() }}
@endsection
