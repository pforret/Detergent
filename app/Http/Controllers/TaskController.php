<?php

namespace App\Http\Controllers;

use App\Models\Day;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Show the form for creating a new task.
     */
    public function create(Day $day)
    {
        return view('tasks.create', compact('day'));
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(Request $request, Day $day)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'type' => 'required|in:major,minor,interrupt,meeting,leisure',
            'status' => 'required|in:pending,in-progress,done,migrated,completed',
            'estimated_minutes' => 'nullable|integer',
            'requester' => 'nullable|string',
            'origin' => 'nullable|string',
        ]);

        $day->tasks()->create($validated);

        return redirect()->route('days.show', $day->date->toDateString())->with('success', 'Task created successfully.');
    }

    /**
     * Show the form for editing the specified task.
     */
    public function edit(Day $day, Task $task)
    {
        return view('tasks.edit', compact('day', 'task'));
    }

    /**
     * Update the specified task in storage.
     */
    public function update(Request $request, Day $day, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'type' => 'required|in:major,minor,interrupt,meeting,leisure',
            'status' => 'required|in:pending,in-progress,done,migrated,completed',
            'estimated_minutes' => 'nullable|integer',
            'actual_minutes' => 'nullable|integer',
            'requester' => 'nullable|string',
            'origin' => 'nullable|string',
        ]);

        $task->update($validated);

        return redirect()->route('days.show', $day->date->toDateString())->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy(Day $day, Task $task)
    {
        $task->delete();

        return redirect()->route('days.show', $day->date->toDateString())->with('success', 'Task deleted successfully.');
    }

    /**
     * Mark the specified task as complete.
     */
    public function markAsComplete(Request $request, Day $day, Task $task)
    {
        $task->update(['status' => 'completed']);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Task marked as complete.', 'task' => $task]);
        }

        return redirect()->route('days.show', $day->date->toDateString())->with('success', 'Task marked as complete.');
    }
}