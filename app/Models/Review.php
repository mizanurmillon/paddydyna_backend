<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $guarded = [];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'booking_id' => 'integer',
        'giver_id' => 'integer',
    ];

    protected $hidden = [
        'updated_at',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function giver()
    {
        return $this->belongsTo(User::class, 'giver_id');
    }
}
