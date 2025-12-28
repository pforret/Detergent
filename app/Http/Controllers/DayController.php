<?php

namespace App\Http\Controllers;

use App\Models\Day;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DayController extends Controller
{
    /**
     * Redirect to today's view.
     */
    public function index()
    {
        return redirect()->route('days.show', ['date' => Carbon::today()->toDateString()]);
    }

    /**
     * Display the specified day.
     */
    public function show(string $date)
    {
        $day = Day::firstOrCreate(['date' => $date]);
        
        // Eager load relationships
        $day->load(['tasks', 'timeBlocks', 'notes']);

        return view('dashboard', compact('day'));
    }
}
