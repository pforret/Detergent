@extends('layouts.etp')

@section('time_grid')
    <h2 class="text-2xl font-bold mb-4">Edit Time Block for {{ $day->date->format('l, F j, Y') }}</h2>
    <form action="{{ route('days.timeBlocks.update', ['day' => $day->id, 'timeBlock' => $timeBlock->id]) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label for="start_time" class="block text-sm font-medium text-gray-700">Start Time</label>
            <input type="time" name="start_time" id="start_time" value="{{ old('start_time', \Carbon\Carbon::parse($timeBlock->start_time)->format('H:i')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
        </div>
        <div>
            <label for="end_time" class="block text-sm font-medium text-gray-700">End Time</label>
            <input type="time" name="end_time" id="end_time" value="{{ old('end_time', \Carbon\Carbon::parse($timeBlock->end_time)->format('H:i')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
        </div>
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Description (Optional)</label>
            <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('description', $timeBlock->description) }}</textarea>
        </div>
        <div>
            <label for="task_id" class="block text-sm font-medium text-gray-700">Associate Task (Optional)</label>
            <select name="task_id" id="task_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <option value="">-- No Task --</option>
                @foreach ($tasks as $task)
                    <option value="{{ $task->id }}" @selected(old('task_id', $timeBlock->task_id) == $task->id)>{{ $task->title }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Update Time Block
        </button>
    </form>

    <form action="{{ route('days.timeBlocks.destroy', ['day' => $day->id, 'timeBlock' => $timeBlock->id]) }}" method="POST" class="mt-4">
        @csrf
        @method('DELETE')
        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" onclick="return confirm('Are you sure you want to delete this time block?')">
            Delete Time Block
        </button>
    </form>
@endsection

@section('tasks_notes')
    <h2 class="text-2xl font-bold mb-4">Current Day Summary</h2>
    {{-- You can include a summary here if needed --}}
@endsection
