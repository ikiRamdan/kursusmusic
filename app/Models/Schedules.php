<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedules extends Model
{
    protected $table = 'schedules';
    public $timestamps = false; // Karena di describe kamu tidak ada created_at

   protected $fillable = ['course_id', 'mentor_id', 'day_of_week', 'start_time', 'end_time', 'room_id', 'capacity'];

    public function course(): BelongsTo {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function mentor(): BelongsTo {
        return $this->belongsTo(Mentor::class, 'mentor_id');
    }

    public function room(): BelongsTo {
        return $this->belongsTo(Room::class, 'room_id');
    }
}