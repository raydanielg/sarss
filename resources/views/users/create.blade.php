@extends('layouts.dashboard')
@section('title', 'Create User - ' . config('app.name'))
@section('page_title', 'Create User')

@section('content')
<div class="mb-6"><a href="{{ route('users.index') }}" class="text-xs text-gray-400 hover:text-gray-600 inline-flex items-center gap-1">&larr; Back to Users</a></div>

<form action="{{ route('users.store') }}" method="POST">
    @csrf
    <div class="bg-white rounded-2xl border border-gray-100 p-6 max-w-lg shadow-sm">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-gray-900">User Details</h3>
                <p class="text-xs text-gray-400">A temporary password will be generated automatically.</p>
            </div>
        </div>
        <div class="space-y-4">
            <div><label class="block text-xs font-semibold text-gray-600 mb-1">Name</label><input type="text" name="name" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none" placeholder="John Doe"></div>
            <div><label class="block text-xs font-semibold text-gray-600 mb-1">Email</label><input type="email" name="email" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none" placeholder="john@example.com"></div>
            <div><label class="block text-xs font-semibold text-gray-600 mb-1">Role</label><select name="role" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none"><option value="">Select Role</option>@foreach($roles as $val=>$label)<option value="{{ $val }}">{{ $label }}</option>@endforeach</select></div>
            <div><label class="block text-xs font-semibold text-gray-600 mb-1">Phone</label><input type="text" name="phone" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none" placeholder="+255..."></div>
        </div>
        <div class="flex gap-3 mt-6">
            <a href="{{ route('users.index') }}" class="px-6 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50">Cancel</a>
            <button type="submit" class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-semibold shadow-sm">Create User</button>
        </div>
    </div>
</form>
@endsection
