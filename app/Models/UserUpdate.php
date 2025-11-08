<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserUpdate extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'surname',
        'email',
        'phone',
        'date_of_birth',
        'avatar',
        'driving_license_or_passport',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
