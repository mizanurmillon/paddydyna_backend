<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ToolBooking extends Model
{
    protected $guarded = [];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'tool_id' => 'integer',
        'address_id' => 'integer',
        'agree_to_terms' => 'boolean',
    ];

    protected $hidden = [
        'updated_at',
    ];

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }

    public function overlaps($start_time, $end_time, $day)
    {
        return self::where('day', $day)
            ->where(function ($query) use ($start_time, $end_time) {
                $query->whereBetween('start_time', [$start_time, $end_time])
                      ->orWhereBetween('end_time', [$start_time, $end_time])
                      ->orWhereRaw('? BETWEEN start_time AND end_time', [$start_time])
                      ->orWhereRaw('? BETWEEN start_time AND end_time', [$end_time]);
            })
            ->exists();
    }
}
