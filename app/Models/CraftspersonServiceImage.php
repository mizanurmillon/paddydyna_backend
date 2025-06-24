<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CraftspersonServiceImage extends Model
{
    protected $guarded = [];

    protected $casts = [
        'id' => 'integer',
        'craftspeople_id' => 'integer',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function craftspeople()
    {
        return $this->belongsTo(Craftsperson::class, 'craftspeople_id');
    }
}
