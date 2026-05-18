@extends('admin.layouts.app')

@section('title', 'Admin Login')

@section('content')
<div class="max-w-md mx-auto mt-16">
    <div class="rounded-3xl bg-white p-8 shadow-sm">
        <h2 class="mb-4 text-2xl font-semibold text-slate-900">Admin Login</h2>

        @if(session('error'))
            <div class="mb-4 rounded-lg bg-rose-50 px-4 py-3 text-rose-700">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="mb-4 rounded-lg bg-rose-50 px-4 py-3 text-rose-700">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ url('/admin/login') }}">
            @csrf

            <label for="name" class="mb-1 block text-sm font-medium text-slate-700">Name</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
                   class="mb-4 w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-2 text-sm text-slate-900 focus:border-slate-400 focus:outline-none" />

            <label for="password" class="mb-1 block text-sm font-medium text-slate-700">Password</label>
            <input id="password" name="password" type="password" required
                   class="mb-6 w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-2 text-sm text-slate-900 focus:border-slate-400 focus:outline-none" />

            <button type="submit" class="w-full rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">Sign in</button>
        </form>
    </div>
</div>
@endsection
