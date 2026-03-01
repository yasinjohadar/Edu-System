<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f3f4f6; }
        .min-h-screen { min-height: 100vh; }
        .bg-gray-100 { background-color: #f3f4f6; }
        .bg-white { background-color: #ffffff; }
        .shadow { box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); }
        .max-w-7xl { max-width: 80rem; margin-left: auto; margin-right: auto; }
        .py-6 { padding-top: 1.5rem; padding-bottom: 1.5rem; }
        .px-4 { padding-left: 1rem; padding-right: 1rem; }
        .py-12 { padding-top: 3rem; padding-bottom: 3rem; }
        .overflow-hidden { overflow: hidden; }
        .shadow-sm { box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
        .rounded-lg { border-radius: 0.5rem; }
        .p-6 { padding: 1.5rem; }
        .text-gray-900 { color: #111827; }
        nav { background-color: #ffffff; border-bottom: 1px solid #e5e7eb; }
        .flex { display: flex; }
        .justify-between { justify-content: space-between; }
        .items-center { align-items: center; }
        .h-16 { height: 4rem; }
        .px-4 { padding-left: 1rem; padding-right: 1rem; }
        a { text-decoration: none; color: #374151; }
        a:hover { color: #111827; }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body>
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl py-6 px-4">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            <div class="max-w-7xl py-12 px-4">
                {{ $slot }}
            </div>
        </main>
    </div>
</body>
</html>
