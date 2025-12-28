<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    protected $fillable = [
        'day_id',
        'content',
    ];

    public function day(): BelongsTo
    {
        return $this->belongsTo(Day::class);
    }
}
