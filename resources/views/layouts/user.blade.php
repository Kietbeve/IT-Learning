<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>ITLearning</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('Image/Logo.png') }}" type="image/png">

    <meta name="description" content="{{ $description ?? '' }}">
    <meta name="keywords" content="{{ $keywords ?? '' }}">
    <meta name="author" content="{{ $author ?? '' }}">

    {{-- Vite CSS & JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body>
    <div class="flex flex-col min-h-screen font-sans text-gray-800">
        <!-- User Header -->
        @include('layouts.patials.user_header')

        <main class="flex-1 p-8 bg-gray-50">
            @yield('content')
            {{ $slot ?? '' }}
        </main>

        <!-- User Footer -->
        @include('layouts.patials.user_footer')
    </div>
    @livewireScripts
    @wireUiScripts
</body>

</html>