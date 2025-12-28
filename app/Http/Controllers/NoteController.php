<?php

namespace App\Http\Controllers;

use App\Models\Day;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Day $day)
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $day->notes()->create($validated);

        return redirect()->route('days.show', $day->date->toDateString())->with('success', 'Note created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Day $day, Note $note)
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $note->update($validated);

        return redirect()->route('days.show', $day->date->toDateString())->with('success', 'Note updated successfully.');
    }
}
