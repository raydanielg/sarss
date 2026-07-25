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

    {{-- Summary Table --}}
    <div class="px-12 mb-8">
        <h3 class="text-sm font-bold text-gray-900 mb-4 pb-2 border-b-2 border-gray-800">Examination Statistics</h3>
        <table class="w-full text-xs border-collapse">
            <thead>
                <tr class="bg-gray-50">
                    <th class="text-left py-2.5 px-4 font-semibold text-gray-600 border border-gray-200">Metric</th>
                    <th class="text-right py-2.5 px-4 font-semibold text-gray-600 border border-gray-200 w-32">Value</th>
                    <th class="text-right py-2.5 px-4 font-semibold text-gray-600 border border-gray-200 w-24">Rate</th>
                </tr>
            </thead>
            <tbody>
                <tr class="hover:bg-gray-50/50"><td class="py-2.5 px-4 text-gray-600 border border-gray-200">Total Candidates</td><td class="py-2.5 px-4 text-right font-bold text-gray-900 border border-gray-200">{{ $totalCandidates }}</td><td class="py-2.5 px-4 text-right text-gray-300 border border-gray-200">—</td></tr>
                <tr class="hover:bg-gray-50/50"><td class="py-2.5 px-4 text-gray-600 border border-gray-200">Participating Schools</td><td class="py-2.5 px-4 text-right font-bold text-gray-900 border border-gray-200">{{ $totalSchools }}</td><td class="py-2.5 px-4 text-right text-gray-300 border border-gray-200">—</td></tr>
                <tr class="hover:bg-gray-50/50"><td class="py-2.5 px-4 text-gray-600 border border-gray-200">Examinable Subjects</td><td class="py-2.5 px-4 text-right font-bold text-gray-900 border border-gray-200">{{ $totalSubjects }}</td><td class="py-2.5 px-4 text-right text-gray-300 border border-gray-200">—</td></tr>
                <tr class="hover:bg-gray-50/50"><td class="py-2.5 px-4 text-gray-600 border border-gray-200">Total Expected Marks</td><td class="py-2.5 px-4 text-right font-bold text-gray-900 border border-gray-200">{{ $totalMarks }}</td><td class="py-2.5 px-4 text-right text-gray-300 border border-gray-200">—</td></tr>
                <tr class="bg-emerald-50/30"><td class="py-2.5 px-4 text-gray-600 border border-gray-200">Marks Entered</td><td class="py-2.5 px-4 text-right font-bold text-emerald-600 border border-gray-200">{{ $enteredMarks }}</td><td class="py-2.5 px-4 text-right font-bold text-emerald-600 border border-gray-200">{{ $completionRate }}%</td></tr>
                <tr class="bg-amber-50/30"><td class="py-2.5 px-4 text-gray-600 border border-gray-200">Marks Verified</td><td class="py-2.5 px-4 text-right font-bold text-amber-600 border border-gray-200">{{ $verifiedMarks }}</td><td class="py-2.5 px-4 text-right font-bold text-amber-600 border border-gray-200">{{ $verificationRate }}%</td></tr>
                <tr class="hover:bg-gray-50/50"><td class="py-2.5 px-4 text-gray-600 border border-gray-200">Pending Marks</td><td class="py-2.5 px-4 text-right font-bold text-gray-900 border border-gray-200">{{ $totalMarks - $enteredMarks }}</td><td class="py-2.5 px-4 text-right text-gray-400 border border-gray-200">{{ $totalMarks > 0 ? round(($totalMarks - $enteredMarks)/$totalMarks*100,1) : 0 }}%</td></tr>
            </tbody>
        </table>
    </div>

    {{-- Examination Info Table --}}
    <div class="px-12 mb-8">
        <h3 class="text-sm font-bold text-gray-900 mb-4 pb-2 border-b-2 border-gray-800">Examination Details</h3>
        <table class="w-full text-xs border-collapse">
            <tbody>
                <tr><td class="py-2 px-4 text-gray-500 border border-gray-200 bg-gray-50/50 w-40 font-medium">Examination Name</td><td class="py-2 px-4 text-gray-900 font-semibold border border-gray-200">{{ $examination->name }}</td></tr>
                <tr><td class="py-2 px-4 text-gray-500 border border-gray-200 bg-gray-50/50 font-medium">Academic Year</td><td class="py-2 px-4 text-gray-900 font-semibold border border-gray-200">{{ $examination->academicYear->year ?? '—' }}</td></tr>
                <tr><td class="py-2 px-4 text-gray-500 border border-gray-200 bg-gray-50/50 font-medium">Exam Type</td><td class="py-2 px-4 text-gray-900 font-semibold border border-gray-200">{{ $examination->examType->name ?? '—' }}</td></tr>
                <tr><td class="py-2 px-4 text-gray-500 border border-gray-200 bg-gray-50/50 font-medium">Region</td><td class="py-2 px-4 text-gray-900 font-semibold border border-gray-200">{{ $examination->region->name ?? '—' }}</td></tr>
                <tr><td class="py-2 px-4 text-gray-500 border border-gray-200 bg-gray-50/50 font-medium">Start Date</td><td class="py-2 px-4 text-gray-900 font-semibold border border-gray-200">{{ $examination->start_date?->format('d F Y') ?? '—' }}</td></tr>
                <tr><td class="py-2 px-4 text-gray-500 border border-gray-200 bg-gray-50/50 font-medium">End Date</td><td class="py-2 px-4 text-gray-900 font-semibold border border-gray-200">{{ $examination->end_date?->format('d F Y') ?? '—' }}</td></tr>
                <tr><td class="py-2 px-4 text-gray-500 border border-gray-200 bg-gray-50/50 font-medium">Status</td><td class="py-2 px-4 text-gray-900 font-semibold border border-gray-200 capitalize">{{ $examination->status }}</td></tr>
                @if($examination->description)
                <tr><td class="py-2 px-4 text-gray-500 border border-gray-200 bg-gray-50/50 font-medium align-top">Description</td><td class="py-2 px-4 text-gray-600 border border-gray-200">{{ $examination->description }}</td></tr>
                @endif
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
