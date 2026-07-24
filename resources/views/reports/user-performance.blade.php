@extends('layouts.dashboard')
@section('title', 'User Performance - ' . config('app.name'))
@section('page_title', 'User Performance Report')

@section('content')
<div class="mb-6"><a href="{{ route('reports.index') }}" class="text-xs text-gray-400 hover:text-gray-600">&larr; Back to Reports</a></div>
<div class="bg-white rounded-xl border border-gray-100 p-5 mb-4"><h2 class="text-lg font-bold text-gray-900">{{ $examination->name }} - Data Entry Performance</h2></div>

<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead><tr class="text-left text-xs text-gray-500 bg-gray-50/50 border-b">
            <th class="px-5 py-3 font-medium">Officer</th>
            <th class="px-5 py-3 font-medium">Email</th>
            <th class="px-5 py-3 font-medium">Marks Entered</th>
        </tr></thead>
        <tbody>
            @forelse($users as $user)
            <tr class="border-b border-gray-50 hover:bg-gray-50/50">
                <td class="px-5 py-3 font-semibold text-gray-900">{{ $user->name }}</td>
                <td class="px-5 py-3 text-gray-600 text-xs">{{ $user->email }}</td>
                <td class="px-5 py-3"><span class="text-lg font-bold text-emerald-600">{{ $user->entered_count }}</span></td>
            </tr>
            @empty
            <tr><td colspan="3" class="px-5 py-8 text-center text-gray-400">No data available.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
