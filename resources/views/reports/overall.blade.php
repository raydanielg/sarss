@extends('layouts.dashboard')
@section('title', 'Overall Report - ' . config('app.name'))
@section('page_title', 'Overall Report')

@section('content')
<div class="mb-6"><a href="{{ route('reports.index') }}" class="text-xs text-gray-400 hover:text-gray-600">&larr; Back to Reports</a></div>

<div class="bg-white rounded-xl border border-gray-100 p-5 mb-4">
    <h2 class="text-lg font-bold text-gray-900">{{ $examination->name }}</h2>
    <p class="text-xs text-gray-400 mt-1">{{ $examination->academicYear->year ?? '' }} · {{ $examination->examType->name ?? '' }} · {{ $examination->region->name ?? '' }}</p>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
    @foreach([['Candidates',$totalCandidates,'emerald'],['Schools',$totalSchools,'amber'],['Subjects',$totalSubjects,'sky'],['Total Marks',$totalMarks,'violet']] as $card)
    <div class="bg-gradient-to-br from-{{ $card[2] }}-500 to-{{ $card[2] }}-700 rounded-xl p-4 text-white">
        <p class="text-[10px] font-medium text-white/80">{{ $card[0] }}</p>
        <p class="text-2xl font-bold mt-1">{{ $card[1] }}</p>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Completion Rate</h3>
        <div class="flex items-center gap-4">
            <div class="relative w-24 h-24">
                <svg class="w-24 h-24 transform -rotate-90"><circle cx="48" cy="48" r="40" stroke="#f3f4f6" stroke-width="8" fill="none"/><circle cx="48" cy="48" r="40" stroke="#10b981" stroke-width="8" fill="none" stroke-dasharray="{{ 2*pi()*40 }}" stroke-dashoffset="{{ 2*pi()*40*(1-$completionRate/100) }}"/></svg>
                <div class="absolute inset-0 flex items-center justify-center"><span class="text-lg font-bold text-gray-900">{{ $completionRate }}%</span></div>
            </div>
            <div class="text-xs space-y-1"><p><span class="text-gray-400">Entered:</span> <span class="font-semibold text-gray-900">{{ $enteredMarks }}</span></p><p><span class="text-gray-400">Total:</span> <span class="font-semibold text-gray-900">{{ $totalMarks }}</span></p></div>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Verification Rate</h3>
        <div class="flex items-center gap-4">
            <div class="relative w-24 h-24">
                <svg class="w-24 h-24 transform -rotate-90"><circle cx="48" cy="48" r="40" stroke="#f3f4f6" stroke-width="8" fill="none"/><circle cx="48" cy="48" r="40" stroke="#f59e0b" stroke-width="8" fill="none" stroke-dasharray="{{ 2*pi()*40 }}" stroke-dashoffset="{{ 2*pi()*40*(1-$verificationRate/100) }}"/></svg>
                <div class="absolute inset-0 flex items-center justify-center"><span class="text-lg font-bold text-gray-900">{{ $verificationRate }}%</span></div>
            </div>
            <div class="text-xs space-y-1"><p><span class="text-gray-400">Verified:</span> <span class="font-semibold text-gray-900">{{ $verifiedMarks }}</span></p><p><span class="text-gray-400">Entered:</span> <span class="font-semibold text-gray-900">{{ $enteredMarks }}</span></p></div>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl border border-gray-100 p-5">
    <h3 class="text-sm font-semibold text-gray-900 mb-4">Progress Bars</h3>
    <div class="space-y-3">
        <div><div class="flex justify-between text-xs mb-1"><span class="text-gray-500">Marks Entered</span><span class="font-semibold">{{ $enteredMarks }} / {{ $totalMarks }} ({{ $completionRate }}%)</span></div><div class="w-full bg-gray-100 rounded-full h-3"><div class="bg-emerald-500 h-3 rounded-full" style="width: {{ $completionRate }}%"></div></div></div>
        <div><div class="flex justify-between text-xs mb-1"><span class="text-gray-500">Marks Verified</span><span class="font-semibold">{{ $verifiedMarks }} / {{ $totalMarks }} ({{ $totalMarks > 0 ? round($verifiedMarks/$totalMarks*100,1) : 0 }}%)</span></div><div class="w-full bg-gray-100 rounded-full h-3"><div class="bg-gold-400 h-3 rounded-full" style="width: {{ $totalMarks > 0 ? round($verifiedMarks/$totalMarks*100,1) : 0 }}%"></div></div></div>
    </div>
</div>
@endsection
