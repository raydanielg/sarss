@extends('layouts.dashboard')
@section('title', 'Panels - ' . config('app.name'))
@section('page_title', 'Panel Management')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <p class="text-sm text-gray-500">Manage marking panels for each subject.</p>
    <a href="{{ route('panels.create') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg transition-colors inline-flex items-center gap-2 shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Create Panel
    </a>
</div>

@php
    $cardThemes = [
        ['from'=>'violet-500','to'=>'violet-600','border'=>'violet-400','statBg'=>'violet-800/30','subText'=>'violet-100'],
        ['from'=>'emerald-500','to'=>'emerald-600','border'=>'emerald-400','statBg'=>'emerald-800/30','subText'=>'emerald-100'],
        ['from'=>'sky-500','to'=>'sky-600','border'=>'sky-400','statBg'=>'sky-800/30','subText'=>'sky-100'],
        ['from'=>'amber-400','to'=>'amber-500','border'=>'amber-300','statBg'=>'amber-800/30','subText'=>'amber-100'],
        ['from'=>'rose-500','to'=>'rose-600','border'=>'rose-400','statBg'=>'rose-800/30','subText'=>'rose-100'],
        ['from'=>'teal-500','to'=>'teal-600','border'=>'teal-400','statBg'=>'teal-800/30','subText'=>'teal-100'],
    ];
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($panels as $i => $panel)
    @php $theme = $cardThemes[$i % count($cardThemes)]; @endphp
    <div class="bg-gradient-to-br from-{{ $theme['from'] }} to-{{ $theme['to'] }} rounded-2xl border border-{{ $theme['border'] }} p-5 text-white relative overflow-hidden hover:shadow-xl transition-all duration-300 hover:-translate-y-0.5">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
        <div class="absolute bottom-0 left-0 w-16 h-16 bg-white/5 rounded-full -ml-8 -mb-8"></div>
        <div class="relative z-10">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-white tracking-tight">{{ $panel->subject->name ?? '—' }}</h3>
                        <p class="text-[10px] {{ $theme['subText'] }} mt-0.5">{{ $panel->examination->name ?? '—' }}</p>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-2 mb-4">
                <div class="text-center {{ $theme['statBg'] }} rounded-lg py-2 backdrop-blur-sm">
                    <p class="text-lg font-bold text-white">{{ $panel->markers_count }}</p>
                    <p class="text-[9px] {{ $theme['subText'] }} font-medium">Markers</p>
                </div>
                <div class="text-center {{ $theme['statBg'] }} rounded-lg py-2 backdrop-blur-sm">
                    <p class="text-lg font-bold text-white">{{ $panel->data_entries_count }}</p>
                    <p class="text-[9px] {{ $theme['subText'] }} font-medium">Data Entry</p>
                </div>
                <div class="text-center {{ $theme['statBg'] }} rounded-lg py-2 backdrop-blur-sm">
                    <p class="text-lg font-bold text-white">{{ $panel->assignments_count }}</p>
                    <p class="text-[9px] {{ $theme['subText'] }} font-medium">Assignments</p>
                </div>
            </div>
            <p class="text-[10px] {{ $theme['subText'] }} mb-3">Moderator: <span class="font-semibold text-white">{{ $panel->moderator->name ?? '—' }}</span></p>
            <div class="flex items-center gap-2">
                <a href="{{ route('panels.show', $panel) }}" class="flex-1 text-center px-3 py-2 bg-white/15 hover:bg-white/25 text-white rounded-lg text-xs font-semibold transition-colors flex items-center justify-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Manage
                </a>
                <button onclick="deletePanel('{{ route("panels.destroy", $panel) }}')" class="w-9 h-9 bg-white/10 hover:bg-red-400/80 text-white rounded-lg transition-colors flex items-center justify-center" title="Delete">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full bg-white rounded-2xl border border-gray-100 p-12 text-center">
        <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
        <p class="text-sm text-gray-400 mb-4">No panels yet.</p>
        <a href="{{ route('panels.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Create your first panel
        </a>
    </div>
    @endforelse
</div>

<script>
function deletePanel(url) {
    Swal.fire({
        title: 'Delete this panel?',
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
