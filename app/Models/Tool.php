<?php

namespace App\Models;

use Carbon\Carbon;
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
             if (strtolower($availability->day) === strtolower($day)) {
                try {
                    $availStart = Carbon::parse($availability->start_time);
                    $availEnd   = Carbon::parse($availability->end_time);
                    $reqStart   = Carbon::parse($start_time);
                    $reqEnd     = Carbon::parse($end_time);
                } catch (\Exception $e) {
                    return false;
                }

                if ($reqStart >= $availStart && $reqEnd <= $availEnd) {
                    return true;
                }
            }
        }

        return false;
    }

    public function toolReviews()
    {
        return $this->hasMany(ToolReview::class);
    }
}
