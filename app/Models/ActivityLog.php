<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    // ⛔ karena tidak pakai updated_at
    public $timestamps = false;

    protected $table = 'activity_logs';

    protected $fillable = [
        'user_id',
        'activity',
        'description',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * 🔗 Relasi ke user (yang melakukan aktivitas)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}