@extends('layouts.app')
@section('title', 'Change Password - ' . config('app.name'))

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-emerald-900 via-emerald-800 to-emerald-950 px-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <div class="text-center mb-6">
                <div class="w-14 h-14 bg-gradient-to-br from-gold-300 to-gold-500 rounded-2xl flex items-center justify-center shadow-lg mx-auto mb-3">
                    <svg class="w-7 h-7 text-emerald-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-6 6M9 21h6m-6 0v-2a4 4 0 014-4h2a4 4 0 014 4v2"/></svg>
                </div>
                <h1 class="text-xl font-bold text-gray-900">Change Your Password</h1>
                <p class="text-xs text-gray-400 mt-1">For security, you must change your temporary password before continuing.</p>
            </div>

            <form action="{{ route('force-password') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">New Password</label>
                        <input type="password" name="password" required minlength="8" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none" placeholder="Enter new password">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Confirm Password</label>
                        <input type="password" name="password_confirmation" required minlength="8" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none" placeholder="Confirm new password">
                    </div>
                </div>
                <button type="submit" class="w-full mt-6 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-semibold transition-colors">Change Password</button>
            </form>
        </div>
    </div>
</div>
@endsection
