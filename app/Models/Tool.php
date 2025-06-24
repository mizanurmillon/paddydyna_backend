<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    protected $guarded = [];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(ToolImage::class);
    }

    public function availabilities()
    {
        return $this->hasMany(ToolAvailability::class);
    }

    public function isToolAvailableForBooking($day, $start_time, $end_time)
    {
        // Check if availability data exists
        if ($this->availabilities->isEmpty()) {
            return false;
        }

        foreach ($this->availabilities as $availability) {
            if ($availability->day == $day) {
                // Check if any overlap between booking and availability
                if (
                    ($start_time < $availability->end_time) &&
                    ($end_time > $availability->start_time)
                ) {
                    return true;
                }
            }
        }

        return false;
    }
}
