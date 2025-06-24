<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ToolAvailability extends Model
{
    protected $guarded = [];

    protected $casts = [
        'id' => 'integer',
        'tool_id' => 'integer',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }
}
