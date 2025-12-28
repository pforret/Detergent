<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeBlock extends Model
{
    use HasFactory;

    protected $fillable = [
        'day_id',
        'task_id',
        'start_time',
        'end_time',
        'description',
    ];

    public function day()
    {
        return $this->belongsTo(Day::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
