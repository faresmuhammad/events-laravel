<?php

namespace App\Models;

use App\Models\Ticket\Ticket;
use App\Models\Ticket\TicketCode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'verification_email_string_code',
        'verification_phone_string_code',
        'email_verified_at',
        'auth_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
        'verification_email_string_code',
        'verification_phone_string_code',
        'auth_token'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    protected static function booted()
    {
        static::created(function (User $user) {
            $user->roles()->sync([Role::SYSTEM_ADMIN], false);
            $user->profiles()->create([
                'name' => "pf-" . strtok($user->name, ' ') . '-' . $user->id
            ]);
        });
    }


    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function following(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'user_event_pivot');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function ticketCodes(): HasMany
    {
        return $this->hasMany(TicketCode::class);
    }

    public function waitings(): HasMany
    {
        return $this->hasMany(WaitingMember::class);
    }

    public function profiles()
    {
        return $this->belongsToMany(Profile::class, 'user_profile_pivot');
    }

    //todo: check if has many works properly in m-m relationship
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role_pivot');
    }

}
