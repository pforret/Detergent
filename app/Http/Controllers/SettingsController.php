<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::firstOrCreate([]);
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'theme' => 'required|in:light,dark',
            'block_size' => 'required|in:10,15,20,30,60',
            'work_start_time' => 'required|date_format:H:i',
            'work_end_time' => 'required|date_format:H:i|after:work_start_time',
            'lunch_start_time' => 'required|date_format:H:i',
            'lunch_end_time' => 'required|date_format:H:i|after:lunch_start_time',
            'morning_break_enabled' => 'nullable|boolean',
            'morning_break_time' => 'required_if:morning_break_enabled,1|nullable|date_format:H:i',
            'afternoon_break_enabled' => 'nullable|boolean',
            'afternoon_break_time' => 'required_if:afternoon_break_enabled,1|nullable|date_format:H:i',
        ]);

        // Checkboxes return 'on' or nothing, need to handle boolean logic if not using '1'/'0' or explicitly casting
        // Laravel's boolean validation rule handles true, false, 1, 0, "1", "0"
        // But un-checked checkboxes are not sent. We need to default them to false.
        
        $validated['morning_break_enabled'] = $request->has('morning_break_enabled');
        $validated['afternoon_break_enabled'] = $request->has('afternoon_break_enabled');

        $settings = Setting::firstOrFail();
        $settings->update($validated);

        return redirect()->route('settings.index')->with('success', 'Settings updated successfully.');
    }
}
