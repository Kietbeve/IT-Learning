@extends('auth::layouts.UserLayout')

@section('title', 'Dashboard')

@section('content')
    <div class="max-w-xl mx-auto p-4">
        <h1 class="text-2xl">user dashboard</h1>

        <p class="mt-2">Xin chào, {{ auth()->user()->name }}</p>

        <form method="POST" action="{{ route('auth.logout') }}" class="mt-4">
            @csrf
            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Đăng xuất</button>
        </form>
    </div>
@endsection
