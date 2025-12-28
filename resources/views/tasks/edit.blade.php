@extends('layouts.etp')

@section('header')
    <div class="flex justify-between items-center">
        <a href="{{ route('days.show', $day->date->toDateString()) }}" class="text-blue-600 hover:text-blue-800 font-semibold text-lg">&larr; Back to Dashboard</a>
        <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100">Edit Task</h1>
        <form action="{{ route('days.tasks.destroy', ['day' => $day->id, 'task' => $task->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this task?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-600 hover:text-red-800 font-semibold text-sm">Delete Task</button>
        </form>
    </div>
@endsection

@section('tasks')
    <h2 class="text-2xl font-bold mb-4 dark:text-gray-200">Task Details</h2>
    <form action="{{ route('days.tasks.update', ['day' => $day->id, 'task' => $task->id]) }}" method="POST" class="space-y-4 bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
        @csrf
        @method('PUT')
        
        <div>
            <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
            <select name="type" id="type" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" onchange="toggleFields()">
                <option value="major" @selected(old('type', $task->type) == 'major')>Major Task</option>
                <option value="minor" @selected(old('type', $task->type) == 'minor')>Minor Task</option>
                <option value="interrupt" @selected(old('type', $task->type) == 'interrupt')>Interrupt</option>
                <option value="meeting" @selected(old('type', $task->type) == 'meeting')>Meeting</option>
                <option value="leisure" @selected(old('type', $task->type) == 'leisure')>Leisure / Break</option>
            </select>
        </div>

        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
            <input type="text" name="title" id="title" value="{{ old('title', $task->title) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
        </div>
        
        <div id="extra-fields" class="{{ in_array(old('type', $task->type), ['interrupt', 'meeting']) ? '' : 'hidden' }} grid grid-cols-1 md:grid-cols-2 gap-4">
             <div>
                <label for="requester" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Requester</label>
                <input type="text" name="requester" id="requester" value="{{ old('requester', $task->requester) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            <div>
                <label for="origin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Origin / Location</label>
                <input type="text" name="origin" id="origin" value="{{ old('origin', $task->origin) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
            <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('description', $task->description) }}</textarea>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="estimated_minutes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estimated Minutes</label>
                <input type="number" name="estimated_minutes" id="estimated_minutes" value="{{ old('estimated_minutes', $task->estimated_minutes) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            <div>
                <label for="actual_minutes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Actual Minutes</label>
                <input type="number" name="actual_minutes" id="actual_minutes" value="{{ old('actual_minutes', $task->actual_minutes) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
        </div>
        
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
            <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <option value="pending" @selected(old('status', $task->status) == 'pending')>Pending</option>
                <option value="in-progress" @selected(old('status', $task->status) == 'in-progress')>In Progress</option>
                <option value="done" @selected(old('status', $task->status) == 'done')>Done</option>
                <option value="migrated" @selected(old('status', $task->status) == 'migrated')>Migrated</option>
                <option value="completed" @selected(old('status', $task->status) == 'completed')>Completed</option>
            </select>
        </div>

        <div class="flex justify-end pt-4">
            <a href="{{ route('days.show', $day->date->toDateString()) }}" class="mr-4 inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Cancel
            </a>
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Update Task
            </button>
        </div>
    </form>

    <script>
        function toggleFields() {
            const type = document.getElementById('type').value;
            const extraFields = document.getElementById('extra-fields');
            if (type === 'interrupt' || type === 'meeting') {
                extraFields.classList.remove('hidden');
            } else {
                extraFields.classList.add('hidden');
            }
        }
    </script>
@endsection