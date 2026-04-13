<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoursePackage extends Model
{
    protected $table = 'course_packages';

    protected $fillable = [ 
        'name',
        'duration_in_month',
        'session_count',
        'price'
    ];

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_package_items', 'course_package_id', 'course_id');
    }

    public function items()
    {
        return $this->hasMany(CourseItem::class, 'course_package_id');
    }

    public function transactions()
{
    return $this->hasMany(TransactionDetail::class, 'package_id');
}
}