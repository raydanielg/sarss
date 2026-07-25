@extends('layouts.dashboard')
@section('title', 'Examinations - ' . config('app.name'))
@section('page_title', 'Examinations')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <p class="text-sm text-gray-500">Manage all examinations.</p>
    <a href="{{ route('examinations.create') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg transition-colors inline-flex items-center gap-2 shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Create Examination
    </a>
</div>

@php
    $cardThemes = [
        ['from'=>'emerald-600','to'=>'emerald-700','border'=>'emerald-500','statBg'=>'emerald-800/40','statText'=>'emerald-100','subText'=>'emerald-200'],
        ['from'=>'sky-500','to'=>'sky-600','border'=>'sky-400','statBg'=>'sky-800/40','statText'=>'sky-100','subText'=>'sky-200'],
        ['from'=>'violet-500','to'=>'violet-600','border'=>'violet-400','statBg'=>'violet-800/40','statText'=>'violet-100','subText'=>'violet-200'],
        ['from'=>'amber-400','to'=>'amber-500','border'=>'amber-300','statBg'=>'amber-800/40','statText'=>'amber-100','subText'=>'amber-200'],
        ['from'=>'rose-500','to'=>'rose-600','border'=>'rose-400','statBg'=>'rose-800/40','statText'=>'rose-100','subText'=>'rose-200'],
        ['from'=>'teal-500','to'=>'teal-600','border'=>'teal-400','statBg'=>'teal-800/40','statText'=>'teal-100','subText'=>'teal-200'],
    ];
    $statusStyles = [
        'draft'    => ['bg'=>'bg-white/20','text'=>'text-white','dot'=>'bg-white/60'],
        'open'     => ['bg'=>'bg-white/20','text'=>'text-white','dot'=>'bg-green-300'],
        'closed'   => ['bg'=>'bg-white/20','text'=>'text-white','dot'=>'bg-yellow-300'],
        'archived' => ['bg'=>'bg-white/20','text'=>'text-white','dot'=>'bg-red-300'],
    ];
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($examinations as $i => $exam)
    @php $theme = $cardThemes[$i % count($cardThemes)]; $ss = $statusStyles[$exam->status]; @endphp
    <div class="bg-gradient-to-br from-{{ $theme['from'] }} to-{{ $theme['to'] }} rounded-2xl border border-{{ $theme['border'] }} p-5 text-white relative overflow-hidden hover:shadow-xl transition-all duration-300 hover:-translate-y-0.5">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
        <div class="absolute bottom-0 left-0 w-16 h-16 bg-white/5 rounded-full -ml-8 -mb-8"></div>
        <div class="relative z-10">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-white tracking-tight">{{ $exam->name }}</h3>
                        <p class="text-[10px] {{ $theme['subText'] }} mt-0.5">{{ $exam->academicYear->year ?? '' }} · {{ $exam->examType->name ?? '' }}</p>
                    </div>
                </div>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-semibold {{ $ss['bg'] }} {{ $ss['text'] }} capitalize">
                    <span class="w-1.5 h-1.5 rounded-full {{ $ss['dot'] }}"></span>{{ $exam->status }}
                </span>
            </div>
            <div class="grid grid-cols-3 gap-2 mb-4">
                <div class="text-center {{ $theme['statBg'] }} rounded-lg py-2 backdrop-blur-sm">
                    <p class="text-lg font-bold text-white">{{ $exam->candidates_count }}</p>
                    <p class="text-[9px] {{ $theme['subText'] }} font-medium">Candidates</p>
                </div>
                <div class="text-center {{ $theme['statBg'] }} rounded-lg py-2 backdrop-blur-sm">
                    <p class="text-lg font-bold text-white">{{ $exam->schools_count }}</p>
                    <p class="text-[9px] {{ $theme['subText'] }} font-medium">Schools</p>
                </div>
                <div class="text-center {{ $theme['statBg'] }} rounded-lg py-2 backdrop-blur-sm">
                    <p class="text-lg font-bold text-white">{{ $exam->subjects_count }}</p>
                    <p class="text-[9px] {{ $theme['subText'] }} font-medium">Subjects</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('examinations.show', $exam) }}" class="flex-1 text-center px-3 py-2 bg-white/15 hover:bg-white/25 text-white rounded-lg text-xs font-semibold transition-colors flex items-center justify-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    View
                </a>
                <a href="{{ route('examinations.edit', $exam) }}" class="flex-1 text-center px-3 py-2 bg-white/15 hover:bg-white/25 text-white rounded-lg text-xs font-semibold transition-colors flex items-center justify-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit
                </a>
                @if($exam->status === 'draft')
                <form action="{{ route('examinations.status', [$exam, 'open']) }}" method="POST" class="inline">@csrf<button class="px-3 py-2 bg-white/15 hover:bg-white/25 text-white rounded-lg text-xs font-semibold transition-colors flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Open
                </button></form>
                @elseif($exam->status === 'open')
                <form action="{{ route('examinations.status', [$exam, 'closed']) }}" method="POST" class="inline">@csrf<button class="px-3 py-2 bg-white/15 hover:bg-white/25 text-white rounded-lg text-xs font-semibold transition-colors flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Close
                </button></form>
                @endif
                <button onclick="deleteExam('{{ route("examinations.destroy", $exam) }}')" class="w-9 h-9 bg-white/10 hover:bg-red-400/80 text-white rounded-lg transition-colors flex items-center justify-center" title="Delete">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full bg-white rounded-2xl border border-gray-100 p-12 text-center">
        <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        <p class="text-sm text-gray-400 mb-4">No examinations yet.</p>
        <a href="{{ route('examinations.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Create your first examination
        </a>
    </div>
    @endforelse
</div>

<script>
function deleteExam(url) {
    Swal.fire({
        title: 'Delete this examination?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        customClass: { popup: 'rounded-xl' },
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            form.innerHTML = '<input type="hidden" name="_token" value="' + document.querySelector('meta[name="csrf-token"]').content + '"><input type="hidden" name="_method" value="DELETE">';
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endsection
