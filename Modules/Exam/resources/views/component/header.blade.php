<header class="bg-white shadow-md px-8 py-4 flex justify-between items-center sticky top-0 z-50">
    <div class="logo">
        <a href="#" class="text-gray-900 text-2xl font-bold font-sans hover:opacity-80 transition-opacity">
            IT<span class="text-blue-600">Learning</span> Exam
        </a>
    </div>
    <nav class="navigation">
        <ul class="flex gap-6">
            <li><a href="#" class="text-gray-600 font-medium font-sans transition-colors duration-300 hover:text-blue-600">Dashboard</a></li>
            <li><a href="#" class="text-gray-600 font-medium font-sans transition-colors duration-300 hover:text-blue-600">My Exams</a></li>
            <li><a href="#" class="text-gray-600 font-medium font-sans transition-colors duration-300 hover:text-blue-600">Results</a></li>
        </ul>
    </nav>
    <div class="user-profile relative group">
        <button class="flex items-center gap-2 text-gray-900 hover:text-blue-600 transition-colors duration-300 focus:outline-none">
            <img src="https://ui-avatars.com/api/?name=User&background=2563eb&color=fff" alt="User Avatar" class="w-10 h-10 rounded-full border border-gray-200 shadow-sm">
            <span class="font-medium font-sans">Tài khoản</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <!-- Dropdown Menu -->
        <div class="absolute right-0 pt-2 w-56 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
            <div class="bg-white border border-gray-100 rounded-md shadow-lg overflow-hidden">
                <div class="py-1">
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">Đăng nhập</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">Đăng ký</a>
                    <div class="border-t border-gray-100 my-1"></div>
                    <a href="{{ route('auth.google.redirect') }}" class="px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 flex items-center gap-2 transition-colors">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                        Đăng nhập bằng Google
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>
