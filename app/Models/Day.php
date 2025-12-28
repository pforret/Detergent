<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Day extends Model
{
    protected $fillable = ['date'];

    protected $casts = [
        'date' => 'date',
    ];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function timeBlocks(): HasMany
    {
        return $this->hasMany(TimeBlock::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }
}
