<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'date',
        'time',
        'duration',
        'status',
        'user_id',
    ];

    // ✅ User relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
