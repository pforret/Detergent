<?php

use App\Http\Controllers\DayController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TimeBlockController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DayController::class, 'index']);
Route::get('/days/{date}', [DayController::class, 'show'])->name('days.show');

Route::resource('days.tasks', TaskController::class)->except(['index', 'show']);
Route::patch('/days/{day}/tasks/{task}/complete', [TaskController::class, 'markAsComplete'])->name('days.tasks.complete');
Route::post('/days/{day}/timeBlocks/populate', [TimeBlockController::class, 'populate'])->name('days.timeBlocks.populate');
Route::post('/days/{day}/timeBlocks/cleanup', [TimeBlockController::class, 'cleanup'])->name('days.timeBlocks.cleanup');
Route::post('/days/{day}/timeBlocks/{timeBlock}/assign', [TimeBlockController::class, 'assign'])->name('days.timeBlocks.assign');
Route::post('/days/{day}/timeBlocks/{timeBlock}/merge', [TimeBlockController::class, 'merge'])->name('days.timeBlocks.merge');
Route::resource('days.timeBlocks', TimeBlockController::class)->except(['index', 'show']);

Route::post('/days/{day}/notes', [NoteController::class, 'store'])->name('days.notes.store');
Route::put('/days/{day}/notes/{note}', [NoteController::class, 'update'])->name('days.notes.update');

Route::get('/settings', [App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
Route::put('/settings', [App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');
