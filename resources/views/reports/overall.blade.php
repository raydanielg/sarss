@extends('layouts.dashboard')
@section('title', 'Overall Report - ' . config('app.name'))
@section('page_title', 'Overall Report')

@section('content')
<div class="mb-6 flex items-center justify-between no-print">
    <a href="{{ route('reports.index') }}" class="text-xs text-gray-400 hover:text-gray-600 inline-flex items-center gap-1">&larr; Back to Reports</a>
    <button onclick="window.print()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold transition-colors inline-flex items-center gap-1.5 shadow-sm">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Download PDF
    </button>
</div>

{{-- A4 Document --}}
<div class="a4-page mx-auto bg-white shadow-lg" style="max-width: 210mm; min-height: 297mm;">
    {{-- Document Header --}}
    <div class="border-b-2 border-gray-800 pb-4 mb-6 px-12 pt-10">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('logo.png') }}" alt="Logo" class="w-12 h-12 object-contain">
                <div>
                    <h1 class="text-xl font-bold text-gray-900 tracking-tight">{{ config('app.name') }}</h1>
                    <p class="text-[10px] text-gray-500">Examination Management System</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-[9px] text-gray-400">Report Generated</p>
                <p class="text-xs font-semibold text-gray-700">{{ now()->format('d M Y, H:i') }}</p>
            </div>
        </div>
    </div>

    {{-- Report Title --}}
    <div class="px-12 mb-6">
        <div class="text-center">
            <p class="text-[10px] text-gray-400 uppercase tracking-widest mb-1">Overall Examination Report</p>
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight">{{ $examination->name }}</h2>
            <div class="flex items-center justify-center gap-3 mt-2 text-xs text-gray-500">
                <span>{{ $examination->academicYear->year ?? '—' }}</span>
                <span class="text-gray-300">|</span>
                <span>{{ $examination->examType->name ?? '—' }}</span>
                <span class="text-gray-300">|</span>
                <span>{{ $examination->region->name ?? '—' }}</span>
            </div>
            <div class="flex items-center justify-center gap-3 mt-1 text-[10px] text-gray-400">
                <span>{{ $examination->start_date?->format('d M Y') }} — {{ $examination->end_date?->format('d M Y') }}</span>
            </div>
        </div>
    </div>

    {{-- KPI Grid --}}
    <div class="px-12 mb-6">
        <div class="grid grid-cols-4 gap-3">
            @php
            $kpis = [
                ['label'=>'Candidates','value'=>$totalCandidates,'icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z','color'=>'emerald'],
                ['label'=>'Schools','value'=>$totalSchools,'icon'=>'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1','color'=>'amber'],
                ['label'=>'Subjects','value'=>$totalSubjects,'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2','color'=>'sky'],
                ['label'=>'Total Marks','value'=>$totalMarks,'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2','color'=>'violet'],
            ];
            @endphp
            @foreach($kpis as $kpi)
            <div class="border border-gray-200 rounded-lg p-3 text-center">
                <div class="w-8 h-8 rounded-lg bg-{{ $kpi['color'] }}-50 flex items-center justify-center mx-auto mb-2">
                    <svg class="w-4 h-4 text-{{ $kpi['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $kpi['icon'] }}"/></svg>
                </div>
                <p class="text-xl font-bold text-gray-900">{{ $kpi['value'] }}</p>
                <p class="text-[9px] text-gray-400 font-medium uppercase tracking-wide">{{ $kpi['label'] }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Progress Section --}}
    <div class="px-12 mb-6">
        <h3 class="text-sm font-bold text-gray-900 mb-3 pb-2 border-b border-gray-100">Progress Summary</h3>
        <div class="grid grid-cols-2 gap-4">
            {{-- Completion Rate --}}
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-semibold text-gray-700">Completion Rate</p>
                    <span class="text-lg font-bold text-emerald-600">{{ $completionRate }}%</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-3 mb-2">
                    <div class="bg-gradient-to-r from-emerald-400 to-emerald-600 h-3 rounded-full" style="width: {{ $completionRate }}%"></div>
                </div>
                <div class="flex justify-between text-[10px] text-gray-400">
                    <span>Entered: <span class="font-semibold text-gray-700">{{ $enteredMarks }}</span></span>
                    <span>Total: <span class="font-semibold text-gray-700">{{ $totalMarks }}</span></span>
                </div>
            </div>
            {{-- Verification Rate --}}
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-semibold text-gray-700">Verification Rate</p>
                    <span class="text-lg font-bold text-amber-600">{{ $verificationRate }}%</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-3 mb-2">
                    <div class="bg-gradient-to-r from-amber-400 to-amber-500 h-3 rounded-full" style="width: {{ $verificationRate }}%"></div>
                </div>
                <div class="flex justify-between text-[10px] text-gray-400">
                    <span>Verified: <span class="font-semibold text-gray-700">{{ $verifiedMarks }}</span></span>
                    <span>Entered: <span class="font-semibold text-gray-700">{{ $enteredMarks }}</span></span>
                </div>
            </div>
        </div>
    </div>

    {{-- Detailed Progress Bars --}}
    <div class="px-12 mb-6">
        <h3 class="text-sm font-bold text-gray-900 mb-3 pb-2 border-b border-gray-100">Detailed Breakdown</h3>
        <div class="space-y-4">
            <div>
                <div class="flex justify-between text-xs mb-1.5">
                    <span class="text-gray-600 font-medium">Marks Entered</span>
                    <span class="font-semibold text-gray-900">{{ $enteredMarks }} / {{ $totalMarks }} ({{ $completionRate }}%)</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-3">
                    <div class="bg-gradient-to-r from-emerald-400 to-emerald-600 h-3 rounded-full" style="width: {{ $completionRate }}%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-xs mb-1.5">
                    <span class="text-gray-600 font-medium">Marks Verified</span>
                    <span class="font-semibold text-gray-900">{{ $verifiedMarks }} / {{ $totalMarks }} ({{ $totalMarks > 0 ? round($verifiedMarks/$totalMarks*100,1) : 0 }}%)</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-3">
                    <div class="bg-gradient-to-r from-amber-400 to-amber-500 h-3 rounded-full" style="width: {{ $totalMarks > 0 ? round($verifiedMarks/$totalMarks*100,1) : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Summary Table --}}
    <div class="px-12 mb-6">
        <h3 class="text-sm font-bold text-gray-900 mb-3 pb-2 border-b border-gray-100">Summary Statistics</h3>
        <table class="w-full text-xs">
            <tbody>
                <tr class="border-b border-gray-50"><td class="py-2 text-gray-500">Total Candidates</td><td class="py-2 text-right font-semibold text-gray-900">{{ $totalCandidates }}</td></tr>
                <tr class="border-b border-gray-50"><td class="py-2 text-gray-500">Participating Schools</td><td class="py-2 text-right font-semibold text-gray-900">{{ $totalSchools }}</td></tr>
                <tr class="border-b border-gray-50"><td class="py-2 text-gray-500">Examinable Subjects</td><td class="py-2 text-right font-semibold text-gray-900">{{ $totalSubjects }}</td></tr>
                <tr class="border-b border-gray-50"><td class="py-2 text-gray-500">Total Expected Marks</td><td class="py-2 text-right font-semibold text-gray-900">{{ $totalMarks }}</td></tr>
                <tr class="border-b border-gray-50"><td class="py-2 text-gray-500">Marks Entered</td><td class="py-2 text-right font-semibold text-emerald-600">{{ $enteredMarks }}</td></tr>
                <tr class="border-b border-gray-50"><td class="py-2 text-gray-500">Marks Verified</td><td class="py-2 text-right font-semibold text-amber-600">{{ $verifiedMarks }}</td></tr>
                <tr><td class="py-2 text-gray-500">Completion Rate</td><td class="py-2 text-right font-bold text-gray-900">{{ $completionRate }}%</td></tr>
            </tbody>
        </table>
    </div>

    {{-- Footer --}}
    <div class="px-12 py-6 mt-auto border-t border-gray-100">
        <div class="flex items-center justify-between text-[9px] text-gray-400">
            <span>{{ config('app.name') }} · Examination Management System</span>
            <span>Generated on {{ now()->format('d M Y, H:i:s') }}</span>
        </div>
    </div>
</div>

<style>
.a4-page {
    font-family: 'Segoe UI', Tahoma, sans-serif;
}
@media print {
    .no-print { display: none !important; }
    body { background: white !important; }
    .a4-page { box-shadow: none !important; max-width: 100% !important; width: 100% !important; min-height: auto !important; }
    @page { size: A4; margin: 0; }
}
@media screen {
    .a4-page { margin-bottom: 2rem; }
}
</style>
@endsection
