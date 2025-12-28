@extends('layouts.etp')

@section('header')
    <div class="flex justify-between items-center">
        <a href="{{ route('days.show', $day->date->toDateString()) }}" class="text-blue-600 hover:text-blue-800 font-semibold text-lg">&larr; Back to Dashboard</a>
        <h1 class="text-3xl font-bold text-gray-800">Edit Interrupt</h1>
        <form action="{{ route('days.interrupts.destroy', ['day' => $day->id, 'interrupt' => $interrupt->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this interrupt?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-600 hover:text-red-800 font-semibold text-sm">Delete Interrupt</button>
        </form>
    </div>
@endsection

@section('tasks')
    <h2 class="text-2xl font-bold mb-4 text-red-700">Edit Interrupt</h2>
    
    <form action="{{ route('days.interrupts.update', ['day' => $day->id, 'interrupt' => $interrupt->id]) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700">What happened? (Title/Description)</label>
            <input type="text" name="title" id="title" value="{{ old('title', $interrupt->title) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50" required>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="requester" class="block text-sm font-medium text-gray-700">Who requested it?</label>
                <input type="text" name="requester" id="requester" value="{{ old('requester', $interrupt->requester) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
            </div>

            <div>
                <label for="origin" class="block text-sm font-medium text-gray-700">Origin</label>
                <select name="origin" id="origin" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                    <option value="email" {{ old('origin', $interrupt->origin) == 'email' ? 'selected' : '' }}>Email</option>
                    <option value="phone" {{ old('origin', $interrupt->origin) == 'phone' ? 'selected' : '' }}>Phone</option>
                    <option value="meeting" {{ old('origin', $interrupt->origin) == 'meeting' ? 'selected' : '' }}>Meeting</option>
                    <option value="in_person" {{ old('origin', $interrupt->origin) == 'in_person' ? 'selected' : '' }}>In Person</option>
                    <option value="other" {{ old('origin', $interrupt->origin) == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
        </div>

        <div>
            <label for="duration" class="block text-sm font-medium text-gray-700">Duration (minutes)</label>
            <input type="number" name="duration" id="duration" value="{{ old('duration', $interrupt->duration) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50" min="0">
        </div>

        <div class="flex justify-end pt-4">
            <a href="{{ route('days.show', $day->date->toDateString()) }}" class="mr-4 inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Cancel
            </a>
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                Update Interrupt
            </button>
        </div>
    </form>
@endsection
