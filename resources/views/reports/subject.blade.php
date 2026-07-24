@extends('layouts.dashboard')
@section('title', 'Subject Report - ' . config('app.name'))
@section('page_title', 'Subject Report')

@section('content')
<div class="mb-6"><a href="{{ route('reports.index') }}" class="text-xs text-gray-400 hover:text-gray-600">&larr; Back to Reports</a></div>
<div class="bg-white rounded-xl border border-gray-100 p-5 mb-4"><h2 class="text-lg font-bold text-gray-900">{{ $examination->name }} - Subject Report</h2></div>

<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead><tr class="text-left text-xs text-gray-500 bg-gray-50/50 border-b">
            <th class="px-5 py-3 font-medium">Subject</th>
            <th class="px-5 py-3 font-medium">Total</th>
            <th class="px-5 py-3 font-medium">Entered</th>
            <th class="px-5 py-3 font-medium">Verified</th>
            <th class="px-5 py-3 font-medium">Rejected</th>
            <th class="px-5 py-3 font-medium">Progress</th>
        </tr></thead>
        <tbody>
            @forelse($data as $row)
            <tr class="border-b border-gray-50 hover:bg-gray-50/50">
                <td class="px-5 py-3 font-semibold text-gray-900">{{ $row['subject']->name }}</td>
                <td class="px-5 py-3 text-gray-600">{{ $row['total'] }}</td>
                <td class="px-5 py-3 text-gray-600">{{ $row['entered'] }}</td>
                <td class="px-5 py-3 text-gray-600">{{ $row['verified'] }}</td>
                <td class="px-5 py-3 text-gray-600">{{ $row['rejected'] }}</td>
                <td class="px-5 py-3"><div class="w-24 bg-gray-100 rounded-full h-2"><div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $row['total'] > 0 ? round($row['entered']/$row['total']*100,1) : 0 }}%"></div></div></td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-5 py-8 text-center text-gray-400">No data available.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
