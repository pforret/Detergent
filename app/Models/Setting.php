<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'theme',
        'block_size',
        'work_start_time',
        'work_end_time',
        'lunch_start_time',
        'lunch_end_time',
        'morning_break_enabled',
        'morning_break_time',
        'afternoon_break_enabled',
        'afternoon_break_time',
    ];

    protected $casts = [
        'morning_break_enabled' => 'boolean',
        'afternoon_break_enabled' => 'boolean',
    ];
}
