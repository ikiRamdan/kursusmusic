<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Schedules;

class Course extends Model
{
    protected $fillable = ['mentor_id', 'name', 'description', 'status', 'image'];

    public function mentor()
    {
        return $this->belongsTo(Mentor::class);
    }

    // Ubah dari hasMany menjadi belongsToMany
    public function packages()
    {
        return $this->belongsToMany(CoursePackage::class, 'course_package_items', 'course_id', 'course_package_id');
    }
   public function schedules()
{
    return $this->hasMany(Schedules::class);
}
// Model Course.php
public function room() {
    return $this->belongsTo(Room::class, 'room_id');
}
}