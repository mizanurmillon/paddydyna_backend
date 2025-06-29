<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $guarded = [];

    protected $casts = [
        'id' => 'integer',
    ];

    protected $hidden = [
        'updated_at',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
