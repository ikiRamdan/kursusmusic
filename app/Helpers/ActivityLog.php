<?php

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

if (!function_exists('logActivity')) {

    /**
     * Menyimpan aktivitas user ke database
     *
     * @param string $activity
     * @param string|null $description
     * @return void
     */
    function logActivity($activity, $description = null)
    {
        try {
            ActivityLog::create([
                'user_id' => Auth::check() ? Auth::id() : null,
                'activity' => $activity,
                'description' => $description,
                'created_at' => now()
            ]);
        } catch (\Exception $e) {
            // Optional: log error ke file laravel
            \Log::error('Activity Log Error: ' . $e->getMessage());
        }
    }
}