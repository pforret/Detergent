@extends('layouts.etp')

@section('header')
    <div class="flex justify-between items-center">
        <a href="{{ route('days.show', ['date' => $day->date->subDay()->toDateString()]) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-semibold text-lg">&larr; Previous Day</a>
        <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100">{{ $day->date->format('l, F j, Y') }}</h1>
        <a href="{{ route('days.show', ['date' => $day->date->addDay()->toDateString()]) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-semibold text-lg">Next Day &rarr;</a>
    </div>
@endsection

@section('time_grid')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-700 dark:text-gray-200">Time Grid</h2>
        <div class="flex space-x-2">
            <form action="{{ route('days.timeBlocks.cleanup', $day->id) }}" method="POST" onsubmit="return confirm('Are you sure? This will remove all unassigned time blocks.');">
                @csrf
                <button type="submit" class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500" title="Remove Unassigned Blocks">
                    Cleanup
                </button>
            </form>
            <form action="{{ route('days.timeBlocks.populate', $day->id) }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-blue-700 dark:text-blue-200 bg-blue-100 dark:bg-blue-900/50 hover:bg-blue-200 dark:hover:bg-blue-900/70 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" title="Populate Day with Blocks">
                    Populate
                </button>
            </form>
            <a href="{{ route('days.timeBlocks.create', $day->id) }}" class="inline-flex items-center p-2 border border-transparent shadow-sm text-sm font-medium rounded-full text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" title="Add Time Block">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
            </a>
        </div>
    </div>
    
    <div class="space-y-0 divide-y divide-gray-100 dark:divide-gray-700">
        @php
            $startHour = 8;
            $endHour = 18;
            
            // Map task types to tailwind classes
            $typeStyles = [
                'major' => 'border-green-700 bg-green-50/30 dark:bg-green-900/20 text-green-800 dark:text-green-300',
                'minor' => 'border-green-300 bg-green-50/20 dark:bg-green-900/10 text-green-600 dark:text-green-400',
                'interrupt' => 'border-orange-500 bg-orange-50/30 dark:bg-orange-900/20 text-orange-700 dark:text-orange-400',
                'meeting' => 'border-blue-500 bg-blue-50/30 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400',
                'leisure' => 'border-gray-500 bg-gray-50/30 dark:bg-gray-800/20 text-gray-600 dark:text-gray-400',
                'default' => 'border-gray-300 dark:border-gray-600 bg-gray-50/50 dark:bg-gray-800/50 text-gray-600 dark:text-gray-400'
            ];
        @endphp

        @if ($day->timeBlocks->isEmpty())
             @for ($i = $startHour; $i < $endHour; $i++)
                <div class="group flex items-start py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out border-l-4 border-transparent hover:border-gray-300 dark:hover:border-gray-500 pl-2">
                    <span class="w-16 text-gray-400 dark:text-gray-500 font-mono text-sm pt-1">{{ sprintf('%02d:00', $i) }}</span>
                    <div class="flex-1 min-h-[1.5rem] border-b border-gray-100 dark:border-gray-700 border-dashed"></div>
                </div>
            @endfor
        @else
            @foreach ($day->timeBlocks->sortBy('start_time') as $timeBlock)
                @php
                    $task = $timeBlock->task;
                    $style = $task ? ($typeStyles[$task->type] ?? $typeStyles['default']) : $typeStyles['default'];
                    $isAssigned = !is_null($task);
                @endphp
                <div 
                    class="flex items-start py-3 group transition duration-150 ease-in-out border-l-4 pl-2 rounded-r-md mb-2 {{ $style }} {{ !$isAssigned ? 'hover:bg-gray-100 dark:hover:bg-gray-700' : '' }} cursor-move"
                    draggable="true"
                    ondragstart="drag(event)"
                    data-type="timeblock"
                    data-id="{{ $timeBlock->id }}"
                    ondrop="drop(event, {{ $timeBlock->id }})" 
                    ondragover="allowDrop(event)"
                    ondragleave="leaveDrop(event)"
                >
                    <span class="w-16 font-mono text-sm font-bold pt-1 opacity-75 pointer-events-none">
                        {{ \Carbon\Carbon::parse($timeBlock->start_time)->format('H:i') }}
                    </span>
                    <div class="flex-1 pointer-events-none">
                        @if ($task)
                            <div class="font-semibold">{{ $task->title }}</div>
                            @if ($task->type === 'interrupt')
                                <div class="text-xs mt-0.5 opacity-75">
                                    <span class="uppercase font-bold tracking-wider text-[0.6rem]">Interrupt</span> &middot; {{ $task->requester }}
                                </div>
                            @elseif ($task->type === 'meeting')
                                 <div class="text-xs mt-0.5 opacity-75">
                                    <span class="uppercase font-bold tracking-wider text-[0.6rem]">Meeting</span> &middot; {{ $task->origin }}
                                </div>
                            @endif
                        @else
                            <div class="font-medium italic opacity-60">{{ $timeBlock->description ?: 'Available' }}</div>
                        @endif
                         <div class="text-xs mt-1 opacity-60">
                            {{ \Carbon\Carbon::parse($timeBlock->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($timeBlock->end_time)->format('H:i') }}
                            <span class="mx-1">&middot;</span>
                            <a href="{{ route('days.timeBlocks.edit', ['day' => $day->id, 'timeBlock' => $timeBlock->id]) }}" class="hover:underline pointer-events-auto">Edit</a>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endsection

@section('tasks')
    <h2 class="text-xl font-bold text-gray-700 dark:text-gray-200 mb-6">The Day's Plan</h2>

    @php
        $sections = [
            'major' => ['title' => 'Top Priorities (Major)', 'color' => 'orange', 'classes' => 'bg-orange-50 dark:bg-orange-900/20 border-orange-100 dark:border-orange-800/50', 'text' => 'text-orange-800 dark:text-orange-300'],
            'minor' => ['title' => 'Other Tasks (Minor)', 'color' => 'gray', 'classes' => 'bg-gray-50 dark:bg-gray-800/50 border-gray-100 dark:border-gray-700', 'text' => 'text-gray-700 dark:text-gray-200'],
            'interrupt' => ['title' => 'Interrupts', 'color' => 'red', 'classes' => 'bg-red-50 dark:bg-red-900/20 border-red-100 dark:border-red-800/50', 'text' => 'text-red-800 dark:text-red-300'],
            'meeting' => ['title' => 'Meetings', 'color' => 'blue', 'classes' => 'bg-blue-50 dark:bg-blue-900/20 border-blue-100 dark:border-blue-800/50', 'text' => 'text-blue-800 dark:text-blue-300'],
            'leisure' => ['title' => 'Leisure / Breaks', 'color' => 'purple', 'classes' => 'bg-purple-50 dark:bg-purple-900/20 border-purple-100 dark:border-purple-800/50', 'text' => 'text-purple-800 dark:text-purple-300'],
        ];
    @endphp

    @foreach ($sections as $type => $config)
        <div class="mb-6 {{ $config['classes'] }} p-4 rounded-lg border">
            <div class="flex justify-between items-center mb-3 border-b border-opacity-20 border-current pb-2 {{ $config['text'] }}">
                <h3 class="text-lg font-bold">{{ $config['title'] }}</h3>
                <a href="{{ route('days.tasks.create', ['day' => $day->id, 'type' => $type]) }}" class="text-xs font-semibold opacity-70 hover:opacity-100 uppercase tracking-wide">
                    + Add
                </a>
            </div>
            <ul class="space-y-3">
                @forelse ($day->tasks->where('type', $type) as $task)
                    <li 
                        class="flex items-start justify-between bg-white dark:bg-gray-800 p-3 rounded shadow-sm border border-opacity-50 cursor-move hover:shadow-md transition-shadow"
                        style="border-color: currentColor"
                        draggable="true" 
                        ondragstart="drag(event)" 
                        data-type="task"
                        data-id="{{ $task->id }}"
                    >
                        <div class="flex items-center pointer-events-none">
                            <span class="h-4 w-4 rounded-full border-2 mr-3 flex-shrink-0 {{ $task->status === 'completed' ? 'bg-current' : '' }}" style="color: inherit; border-color: currentColor"></span>
                            <span class="{{ $task->status === 'completed' ? 'line-through text-gray-400 dark:text-gray-500' : 'text-gray-800 dark:text-gray-200 font-medium' }}">{{ $task->title }}</span>
                        </div>
                        <a href="{{ route('days.tasks.edit', ['day' => $day->id, 'task' => $task->id]) }}" class="text-gray-400 dark:text-gray-500 hover:text-blue-600 dark:hover:text-blue-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </a>
                    </li>
                @empty
                    <li class="text-sm italic opacity-60 {{ $config['text'] }}">No items.</li>
                @endforelse
            </ul>
        </div>
    @endforeach

    <script>
        function allowDrop(ev) {
            ev.preventDefault();
            ev.currentTarget.classList.add('ring-2', 'ring-blue-400', 'bg-blue-50', 'dark:bg-blue-900/40');
        }
        
        function leaveDrop(ev) {
             ev.currentTarget.classList.remove('ring-2', 'ring-blue-400', 'bg-blue-50', 'dark:bg-blue-900/40');
        }

        function drag(ev) {
            ev.dataTransfer.setData("type", ev.target.dataset.type);
            ev.dataTransfer.setData("id", ev.target.dataset.id);
        }

        function drop(ev, timeBlockId) {
            ev.preventDefault();
            ev.currentTarget.classList.remove('ring-2', 'ring-blue-400', 'bg-blue-50', 'dark:bg-blue-900/40');
            
            var type = ev.dataTransfer.getData("type");
            var id = ev.dataTransfer.getData("id");
            var dayId = {{ $day->id }}; 

            if (!id) return;

            if (type === 'task') {
                fetch(`/days/${dayId}/timeBlocks/${timeBlockId}/assign`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    }
                })
                .catch(error => console.error('Error:', error));
            } else if (type === 'timeblock') {
                fetch(`/days/${dayId}/timeBlocks/${timeBlockId}/merge`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ source_block_id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }
    </script>
@endsection

@section('notes')
    <div class="h-full flex flex-col">
        <h2 class="text-xl font-bold text-gray-700 dark:text-gray-200 mb-4">Notes & Scribbles</h2>
        <div class="flex-1 bg-yellow-50 dark:bg-yellow-900/10 rounded-lg border border-yellow-200 dark:border-yellow-900/30 p-4 shadow-inner">
            <form action="{{ $day->notes->isNotEmpty() ? route('days.notes.update', ['day' => $day->id, 'note' => $day->notes->first()->id]) : route('days.notes.store', $day->id) }}" method="POST" class="h-full flex flex-col">
                @csrf
                @if ($day->notes->isNotEmpty())
                    @method('PUT')
                @endif
                <textarea name="content" id="content" class="w-full h-full p-2 bg-transparent border-none resize-none focus:ring-0 text-gray-700 dark:text-gray-200 leading-relaxed placeholder-gray-400 dark:placeholder-gray-600" placeholder="Capture your thoughts, interruptions, and ideas here...">{{ $day->notes->isNotEmpty() ? $day->notes->first()->content : '' }}</textarea>
                <div class="mt-2 text-right">
                    <button type="submit" class="inline-flex justify-center py-1 px-3 border border-transparent shadow-sm text-xs font-medium rounded text-yellow-900 dark:text-yellow-100 bg-yellow-200 dark:bg-yellow-800 hover:bg-yellow-300 dark:hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection