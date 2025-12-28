<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ isset($settings) && $settings->theme === 'dark' ? 'dark' : '' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-100 transition-colors duration-200">
    <div class="min-h-screen flex flex-col py-6">
        <div class="w-full max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Header: Date Navigation --}}
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md mb-6 relative">
                @yield('header')
                <div class="absolute top-4 right-4 translate-x-2 -translate-y-2">
                     <a href="{{ route('settings.index') }}" class="text-gray-300 hover:text-gray-500 dark:text-gray-600 dark:hover:text-gray-400 opacity-50 hover:opacity-100 transition-opacity" title="Settings">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                {{-- Column 1: Time Grid (Narrower) --}}
                <div class="lg:col-span-1 bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md h-full transition-colors duration-200">
                    @yield('time_grid')
                </div>

                {{-- Column 2: Tasks (Wider) --}}
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md h-full transition-colors duration-200">
                    @yield('tasks')
                </div>

                {{-- Column 3: Notes (Standard) --}}
                <div class="lg:col-span-1 bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md h-full transition-colors duration-200">
                    @yield('notes')
                </div>
            </div>
        </div>
    </div>
</body>
</html>
