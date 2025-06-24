<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * Get the identifier that will be stored in the JWT subject claim.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey(); // Return the primary key of the user (id)
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'provider',
        'provider_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'agree_to_terms'    => 'boolean',
            'is_premium'        => 'boolean',
            'id'                => 'integer',
        ];
    }

    public function craftsperson()
    {
        return $this->hasOne(Craftsperson::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function isAvailableForBooking($day, $start_time, $end_time)
    {
        // Check if availability data exists
        if (! $this->craftsperson || $this->craftsperson->availability->isEmpty()) {
            return false;
        }

        foreach ($this->craftsperson->availability as $availability) {
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

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function giver_reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'giver_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'user_id');
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class, 'sender_id', 'id');
    }

}
