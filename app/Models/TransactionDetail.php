<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{

public $timestamps = false;
    protected $fillable = [
        'transaction_id',
        'course_id',
        'package_id',
        'price',
        'qty',
        'subtotal'
    ];

    // 🔗 Relasi ke transaksi
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    // 🔗 Relasi ke paket
    public function package()
    {
        return $this->belongsTo(CoursePackage::class, 'package_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}