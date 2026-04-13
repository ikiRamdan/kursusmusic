<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'invoice_code',
        'customer_name',
        'customer_phone',
        'customer_address',
        'user_id',
        'total_price',
        'total_paid',      // Kolom Baru
        'payment_amount',
        'change_amount',
        'payment_status',  // Kolom Baru (dp/paid)
        'status'           // Status umum (paid/pending)
    ];

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}