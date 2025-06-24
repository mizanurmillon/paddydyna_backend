<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Craftsperson extends Model
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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function availability()
    {
        return $this->hasMany(CraftspersonAvailability::class, 'craftspeople_id');
    }

    public function images()
    {
        return $this->hasMany(CraftspersonServiceImage::class, 'craftspeople_id');
    }
}
