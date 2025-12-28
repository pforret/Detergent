@extends('layouts.etp')

@section('header')
    <div class="flex justify-between items-center">
        <a href="{{ url('/') }}" class="text-blue-600 hover:text-blue-800 font-semibold text-lg dark:text-blue-400 dark:hover:text-blue-300">&larr; Back to Dashboard</a>
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Settings</h1>
        <div></div>
    </div>
@endsection

@section('tasks')
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold text-gray-700 dark:text-gray-200 mb-6">Application Preferences</h2>
        
        <form action="{{ route('settings.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            {{-- Theme Selection --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Theme</label>
                <div class="flex items-center space-x-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="theme" value="light" class="form-radio text-blue-600" {{ $settings->theme === 'light' ? 'checked' : '' }}>
                        <span class="ml-2 text-gray-700 dark:text-gray-300">Light</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="theme" value="dark" class="form-radio text-blue-600" {{ $settings->theme === 'dark' ? 'checked' : '' }}>
                        <span class="ml-2 text-gray-700 dark:text-gray-300">Dark</span>
                    </label>
                </div>
            </div>

            {{-- Block Size Selection --}}
            <div>
                <label for="block_size" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Time Block Size (minutes)</label>
                <select name="block_size" id="block_size" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @foreach([10, 15, 20, 30, 60] as $size)
                        <option value="{{ $size }}" {{ $settings->block_size == $size ? 'selected' : '' }}>{{ $size }} minutes</option>
                    @endforeach
                </select>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Default duration for new time blocks.</p>
            </div>

            <hr class="border-gray-200 dark:border-gray-700 my-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Work Schedule</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="work_start_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Work Start</label>
                    <input type="time" name="work_start_time" id="work_start_time" value="{{ old('work_start_time', $settings->work_start_time) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label for="work_end_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Work End</label>
                    <input type="time" name="work_end_time" id="work_end_time" value="{{ old('work_end_time', $settings->work_end_time) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="lunch_start_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Lunch Start</label>
                    <input type="time" name="lunch_start_time" id="lunch_start_time" value="{{ old('lunch_start_time', $settings->lunch_start_time) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label for="lunch_end_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Lunch End</label>
                    <input type="time" name="lunch_end_time" id="lunch_end_time" value="{{ old('lunch_end_time', $settings->lunch_end_time) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <div class="mt-6 space-y-4">
                <div class="flex items-center">
                    <input type="checkbox" name="morning_break_enabled" id="morning_break_enabled" value="1" {{ $settings->morning_break_enabled ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="morning_break_enabled" class="ml-2 block text-sm text-gray-700 dark:text-gray-300 w-40">
                        Morning Break
                    </label>
                    <input type="time" name="morning_break_time" value="{{ old('morning_break_time', $settings->morning_break_time) }}" class="ml-4 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="afternoon_break_enabled" id="afternoon_break_enabled" value="1" {{ $settings->afternoon_break_enabled ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="afternoon_break_enabled" class="ml-2 block text-sm text-gray-700 dark:text-gray-300 w-40">
                        Afternoon Break
                    </label>
                    <input type="time" name="afternoon_break_time" value="{{ old('afternoon_break_time', $settings->afternoon_break_time) }}" class="ml-4 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                </div>
            </div>

            <div class="pt-4 border-t border-gray-200 dark:border-gray-700 mt-6">
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
@endsection

@section('time_grid')
    <div class="text-center text-gray-500 dark:text-gray-400 italic mt-10">
        Settings affect how your day is displayed and planned.
    </div>
@endsection

@section('notes')
    <div class="text-center text-gray-500 dark:text-gray-400 italic mt-10">
        Changes are applied immediately.
    </div>
@endsection