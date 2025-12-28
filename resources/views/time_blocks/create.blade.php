@extends('layouts.etp')

@section('time_grid')
    <h2 class="text-2xl font-bold mb-4">Create New Time Block for {{ $day->date->format('l, F j, Y') }}</h2>
    <form action="{{ route('days.timeBlocks.store', $day->id) }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label for="start_time" class="block text-sm font-medium text-gray-700">Start Time</label>
            <input type="time" name="start_time" id="start_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
        </div>
        <div>
            <label for="end_time" class="block text-sm font-medium text-gray-700">End Time</label>
            <input type="time" name="end_time" id="end_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
        </div>
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Description (Optional)</label>
            <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
        </div>
        <div>
            <label for="task_id" class="block text-sm font-medium text-gray-700">Associate Task (Optional)</label>
            <select name="task_id" id="task_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <option value="">-- No Task --</option>
                @foreach ($tasks as $task)
                    <option value="{{ $task->id }}">{{ $task->title }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Create Time Block
        </button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startTimeInput = document.getElementById('start_time');
            const endTimeInput = document.getElementById('end_time');
            const blockSize = {{ $blockSize ?? 15 }};

            startTimeInput.addEventListener('change', function() {
                if (this.value) {
                    const [hours, minutes] = this.value.split(':').map(Number);
                    const date = new Date();
                    date.setHours(hours);
                    date.setMinutes(minutes + blockSize);

                    const newHours = String(date.getHours()).padStart(2, '0');
                    const newMinutes = String(date.getMinutes()).padStart(2, '0');
                    
                    endTimeInput.value = `${newHours}:${newMinutes}`;
                }
            });
        });
    </script>
@endsection

@section('tasks_notes')
    <h2 class="text-2xl font-bold mb-4">Current Day Summary</h2>
    {{-- You can include a summary here if needed --}}
@endsection
