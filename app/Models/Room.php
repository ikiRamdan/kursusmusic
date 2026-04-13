<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'capacity'];

    // Relasi ke Mentor
    public function mentors(): HasMany
    {
        return $this->hasMany(Mentor::class);
    }
}