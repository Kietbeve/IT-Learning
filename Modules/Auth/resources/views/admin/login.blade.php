<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('Image/logo.png') }}" type="image/png">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
    
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
        }
    </style>
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-800 flex items-center justify-center min-h-screen">
    
    <div class="w-full max-w-md">
        <div class="rounded-3xl bg-white p-8 shadow-sm border border-slate-100 mx-4 md:mx-0">
            <div class="text-center mb-8">
                <img src="{{ asset('Image/logo.png') }}" alt="Logo" class="h-12 mx-auto mb-4">
                <h2 class="text-2xl font-semibold text-slate-900">Admin Login</h2>
                <p class="text-slate-500 text-sm mt-1">Đăng nhập vào bảng quản trị</p>
            </div>

            @if(session('error'))
                <div class="mb-4 rounded-lg bg-rose-50 px-4 py-3 text-rose-700 text-sm">{{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div class="mb-4 rounded-lg bg-rose-50 px-4 py-3 text-rose-700 text-sm">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ url('/admin/login') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="mb-1 block text-sm font-medium text-slate-700">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                        class="w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-2 text-sm text-slate-900 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 focus:bg-white transition-all outline-none" />
                </div>

                <div class="mb-6">
                    <label for="password" class="mb-1 block text-sm font-medium text-slate-700">Password</label>
                    <input id="password" name="password" type="password" required
                        class="w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-2 text-sm text-slate-900 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 focus:bg-white transition-all outline-none" />
                </div>

                <button type="submit"
                    class="w-full rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800 transition-colors shadow-sm">
                    Sign in
                </button>
            </form>
        </div>
    </div>

</body>
</html>