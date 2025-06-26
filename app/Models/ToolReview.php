<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ToolReview extends Model
{
    protected $guarded = [];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'tool_booking_id' => 'integer',
    ];

    protected $hidden = [
        'updated_at',
    ];

    public function tool_booking()
    {
        return $this->belongsTo(ToolBooking::class, 'booking_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
