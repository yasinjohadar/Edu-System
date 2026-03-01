<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="text-xl font-bold text-gray-800">
                    {{ config('app.name', 'Laravel') }}
                </a>
            </div>

            <div class="flex items-center">
                <span class="text-gray-700">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="mr-4">
                    @csrf
                    <button type="submit" class="text-gray-600 hover:text-gray-900">تسجيل الخروج</button>
                </form>
            </div>
        </div>
    </div>
</nav>
