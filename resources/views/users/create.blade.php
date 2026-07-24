@extends('layouts.dashboard')
@section('title', 'Create User - ' . config('app.name'))
@section('page_title', 'Create User')

@section('content')
<div class="mb-6"><a href="{{ route('users.index') }}" class="text-xs text-gray-400 hover:text-gray-600">&larr; Back to Users</a></div>

<form action="{{ route('users.store') }}" method="POST">
    @csrf
    <div class="bg-white rounded-xl border border-gray-100 p-6 max-w-lg">
        <h3 class="text-sm font-bold text-gray-900 mb-4">User Details</h3>
        <p class="text-xs text-gray-400 mb-4">A temporary password will be generated. The user will be forced to change it on first login.</p>
        <div class="space-y-4">
            <div><label class="block text-xs font-semibold text-gray-600 mb-1">Name</label><input type="text" name="name" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm outline-none" placeholder="John Doe"></div>
            <div><label class="block text-xs font-semibold text-gray-600 mb-1">Email</label><input type="email" name="email" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm outline-none" placeholder="john@example.com"></div>
            <div><label class="block text-xs font-semibold text-gray-600 mb-1">Role</label><select name="role" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm outline-none">@foreach($roles as $val=>$label)<option value="{{ $val }}">{{ $label }}</option>@endforeach</select></div>
            <div><label class="block text-xs font-semibold text-gray-600 mb-1">Phone</label><input type="text" name="phone" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm outline-none"></div>
        </div>
        <div class="flex gap-3 mt-6">
            <a href="{{ route('users.index') }}" class="px-6 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50">Cancel</a>
            <button type="submit" class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-semibold">Create User</button>
        </div>
    </div>
</form>
@endsection
