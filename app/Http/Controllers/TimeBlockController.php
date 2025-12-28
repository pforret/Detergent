<?php

namespace App\Http\Controllers;

use App\Models\Day;
use App\Models\TimeBlock;
use App\Models\Setting;
use App\Models\Task;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TimeBlockController extends Controller
{
    /**
     * Show the form for creating a new time block.
     */
    public function create(Day $day)
    {
        $tasks = $day->tasks()->get();
        $settings = Setting::first();
        $blockSize = $settings ? $settings->block_size : 15;
        
        return view('time_blocks.create', compact('day', 'tasks', 'blockSize'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Day $day)
    {
        $validated = $request->validate([
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'description' => 'nullable|string',
            'task_id' => 'nullable|exists:tasks,id',
        ]);

        $day->timeBlocks()->create($validated);

        return redirect()->route('days.show', $day->date->toDateString())->with('success', 'Time Block created successfully.');
    }

    /**
     * Populate the day with empty time blocks based on settings.
     */
    public function populate(Day $day)
    {
        $settings = Setting::first();
        $blockSize = $settings ? $settings->block_size : 15;
        
        $workStart = Carbon::parse($settings->work_start_time ?? '09:00');
        $workEnd = Carbon::parse($settings->work_end_time ?? '17:00');
        
        // Collect defined breaks
        $breaks = [];
        
        // Lunch
        $lunchStart = Carbon::parse($settings->lunch_start_time ?? '13:00');
        $lunchEnd = Carbon::parse($settings->lunch_end_time ?? '14:00');
        if ($lunchStart < $lunchEnd) {
            $breaks[] = ['start' => $lunchStart, 'end' => $lunchEnd, 'name' => 'Lunch'];
        }
        
        // Morning Break
        if ($settings->morning_break_enabled) {
            $start = Carbon::parse($settings->morning_break_time ?? '11:00');
            $end = $start->copy()->addMinutes(15);
            $breaks[] = ['start' => $start, 'end' => $end, 'name' => 'Morning Break'];
        }
        
        // Afternoon Break
        if ($settings->afternoon_break_enabled) {
            $start = Carbon::parse($settings->afternoon_break_time ?? '16:00');
            $end = $start->copy()->addMinutes(15);
            $breaks[] = ['start' => $start, 'end' => $end, 'name' => 'Afternoon Break'];
        }
        
        // Sort breaks
        usort($breaks, function ($a, $b) {
            return $a['start'] <=> $b['start'];
        });
        
        $current = $workStart->copy();
        
        foreach ($breaks as $break) {
            // Fill work time before this break
            if ($current < $break['start']) {
                $this->fillWorkGap($day, $current, $break['start'], $blockSize);
            }
            
            // Create the break block if within work hours
            if ($break['start'] >= $workStart && $break['end'] <= $workEnd) {
                 $this->createBlockIfNotOverlapping($day, $break['start'], $break['end'], $break['name']);
            }
            
            $current = $break['end']->copy();
        }
        
        // Fill remaining work time
        if ($current < $workEnd) {
            $this->fillWorkGap($day, $current, $workEnd, $blockSize);
        }

        return redirect()->route('days.show', $day->date->toDateString())->with('success', 'Day populated with schedule.');
    }

    private function fillWorkGap(Day $day, Carbon $start, Carbon $end, int $blockSize)
    {
        $current = $start->copy();
        while ($current->copy()->addMinutes($blockSize) <= $end) {
            $blockEnd = $current->copy()->addMinutes($blockSize);
            $this->createBlockIfNotOverlapping($day, $current, $blockEnd, 'Available');
            $current = $blockEnd;
        }
    }

    private function createBlockIfNotOverlapping(Day $day, Carbon $start, Carbon $end, string $description)
    {
        $overlaps = $day->timeBlocks()
            ->where(function ($q) use ($start, $end) {
                $q->where('start_time', '<', $end->format('H:i:00'))
                  ->where('end_time', '>', $start->format('H:i:00'));
            })
            ->exists();

        if (!$overlaps) {
            $day->timeBlocks()->create([
                'start_time' => $start->format('H:i'),
                'end_time' => $end->format('H:i'),
                'description' => $description,
            ]);
        }
    }

    public function cleanup(Day $day)
    {
        $day->timeBlocks()
            ->whereNull('task_id')
            ->delete();

        return redirect()->route('days.show', $day->date->toDateString())->with('success', 'Unassigned time blocks removed.');
    }

    /**
     * Show the form for editing the specified time block.
     */
    public function edit(Day $day, TimeBlock $timeBlock)
    {
        $tasks = $day->tasks()->get();
        return view('time_blocks.edit', compact('day', 'timeBlock', 'tasks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Day $day, TimeBlock $timeBlock)
    {
        $validated = $request->validate([
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'description' => 'nullable|string',
            'task_id' => 'nullable|exists:tasks,id',
        ]);

        $timeBlock->update($validated);

        return redirect()->route('days.show', $day->date->toDateString())->with('success', 'Time Block updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Day $day, TimeBlock $timeBlock)
    {
        $timeBlock->delete();

        return redirect()->route('days.show', $day->date->toDateString())->with('success', 'Time Block deleted successfully.');
    }

    public function assign(Request $request, Day $day, TimeBlock $timeBlock)
    {
        $request->validate([
            'id' => 'required|integer|exists:tasks,id',
        ]);

        $timeBlock->update([
            'task_id' => $request->id,
        ]);

        return response()->json(['success' => true]);
    }

    public function merge(Request $request, Day $day, TimeBlock $timeBlock)
    {
        $request->validate([
            'source_block_id' => 'required|exists:time_blocks,id',
        ]);

        $sourceBlock = TimeBlock::find($request->source_block_id);
        
        if (!$sourceBlock || $sourceBlock->id === $timeBlock->id) {
             return response()->json(['success' => false, 'message' => 'Invalid source block.'], 422);
        }

        // Normalize times for comparison (HH:MM:00 vs HH:MM)
        $targetStart = Carbon::parse($timeBlock->start_time)->format('H:i');
        $targetEnd = Carbon::parse($timeBlock->end_time)->format('H:i');
        $sourceStart = Carbon::parse($sourceBlock->start_time)->format('H:i');
        $sourceEnd = Carbon::parse($sourceBlock->end_time)->format('H:i');

        $isPreceding = $sourceEnd === $targetStart;
        $isSucceeding = $sourceStart === $targetEnd;
        
        if (!$isPreceding && !$isSucceeding) {
            return response()->json(['success' => false, 'message' => 'Blocks are not adjacent.'], 422);
        }

        // Determine merge direction (earliest start, latest end)
        $newStart = $isPreceding ? $sourceStart : $targetStart;
        $newEnd = $isSucceeding ? $sourceEnd : $targetEnd;
        
        // Prioritize Task/Content info
        // If target has task, keep it. If not, take source task.
        $taskId = $timeBlock->task_id ?? $sourceBlock->task_id;
        
        // Description logic: prefer "real" description over "Available"
        $desc = 'Available';
        if ($timeBlock->description && $timeBlock->description !== 'Available') {
            $desc = $timeBlock->description;
        } elseif ($sourceBlock->description && $sourceBlock->description !== 'Available') {
            $desc = $sourceBlock->description;
        }

        $timeBlock->update([
            'start_time' => $newStart,
            'end_time' => $newEnd,
            'task_id' => $taskId,
            'description' => $desc
        ]);
        
        $sourceBlock->delete();

        return response()->json(['success' => true]);
    }
}