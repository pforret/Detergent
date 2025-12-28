<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'day_id',
        'title',
        'description',
        'type', // major, minor, interrupt, meeting, leisure
        'status', // pending, in-progress, done, migrated, completed
        'estimated_minutes',
        'actual_minutes',
        'requester',
        'origin',
    ];

    public function day()
    {
        return $this->belongsTo(Day::class);
    }
}