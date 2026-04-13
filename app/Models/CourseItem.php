<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseItem extends Model
{
    protected $table = 'course_package_items';

    protected $fillable = [
        'course_package_id',
        'course_id'
    ];

    public function package()
    {
        return $this->belongsTo(CoursePackage::class, 'course_package_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}