@extends('layouts.dashboard')
@section('title', 'School Report - ' . config('app.name'))
@section('page_title', 'School Report')

@section('content')
<div class="mb-6"><a href="{{ route('reports.index') }}" class="text-xs text-gray-400 hover:text-gray-600">&larr; Back to Reports</a></div>

<div class="bg-white rounded-xl border border-gray-100 p-5 mb-4"><h2 class="text-lg font-bold text-gray-900">{{ $examination->name }} - School Report</h2></div>

<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead><tr class="text-left text-xs text-gray-500 bg-gray-50/50 border-b">
            <th class="px-5 py-3 font-medium">School</th>
            <th class="px-5 py-3 font-medium">District</th>
            <th class="px-5 py-3 font-medium">Candidates</th>
            <th class="px-5 py-3 font-medium">Entered</th>
            <th class="px-5 py-3 font-medium">Verified</th>
            <th class="px-5 py-3 font-medium">Pending</th>
            <th class="px-5 py-3 font-medium">Progress</th>
        </tr></thead>
        <tbody>
            @forelse($data as $row)
            <tr class="border-b border-gray-50 hover:bg-gray-50/50">
                <td class="px-5 py-3 font-semibold text-gray-900">{{ $row['school']->name }}</td>
                <td class="px-5 py-3 text-gray-600 text-xs">{{ $row['school']->district->name ?? '—' }}</td>
                <td class="px-5 py-3 text-gray-600">{{ $row['candidates'] }}</td>
                <td class="px-5 py-3 text-gray-600">{{ $row['entered'] }}</td>
                <td class="px-5 py-3 text-gray-600">{{ $row['verified'] }}</td>
                <td class="px-5 py-3 text-gray-600">{{ $row['pending'] }}</td>
                <td class="px-5 py-3"><div class="w-24 bg-gray-100 rounded-full h-2"><div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $row['percentage'] }}%"></div></div><span class="text-[10px] text-gray-400 mt-0.5 block">{{ $row['percentage'] }}%</span></td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-5 py-8 text-center text-gray-400">No data available.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
