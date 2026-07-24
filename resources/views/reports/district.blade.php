@extends('layouts.dashboard')
@section('title', 'District Report - ' . config('app.name'))
@section('page_title', 'District Report')

@section('content')
<div class="mb-6"><a href="{{ route('reports.index') }}" class="text-xs text-gray-400 hover:text-gray-600">&larr; Back to Reports</a></div>
<div class="bg-white rounded-xl border border-gray-100 p-5 mb-4"><h2 class="text-lg font-bold text-gray-900">{{ $examination->name }} - District Report</h2></div>

<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead><tr class="text-left text-xs text-gray-500 bg-gray-50/50 border-b">
            <th class="px-5 py-3 font-medium">District</th>
            <th class="px-5 py-3 font-medium">Schools</th>
            <th class="px-5 py-3 font-medium">Total Marks</th>
            <th class="px-5 py-3 font-medium">Entered</th>
            <th class="px-5 py-3 font-medium">Verified</th>
        </tr></thead>
        <tbody>
            @forelse($data as $row)
            <tr class="border-b border-gray-50 hover:bg-gray-50/50">
                <td class="px-5 py-3 font-semibold text-gray-900">{{ $row['district']->name }}</td>
                <td class="px-5 py-3 text-gray-600">{{ $row['district']->schools_count }}</td>
                <td class="px-5 py-3 text-gray-600">{{ $row['total'] }}</td>
                <td class="px-5 py-3 text-gray-600">{{ $row['entered'] }}</td>
                <td class="px-5 py-3 text-gray-600">{{ $row['verified'] }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-5 py-8 text-center text-gray-400">No data available.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
