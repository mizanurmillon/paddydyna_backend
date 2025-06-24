<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageReact extends Model
{
    protected $guarded = [];

    protected $casts = [
        'user_id' => 'integer',
        'message_id' => 'integer',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function message()
    {
        return $this->belongsTo(Message::class);
    }
}
