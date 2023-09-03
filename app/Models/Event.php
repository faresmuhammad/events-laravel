<?php

namespace App\Models;

use App\Models\Ticket\Ticket;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'location',
        'location_coordinates',
        'event_image',
        'is_live',
        'user_id',
        'profile_id',
    ];

    protected $casts = [
        'location_coordinates' => 'json'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class,'user_event_pivot');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    protected static function booted(): void
    {
        static::creating(function (Event $event) {
            $event->user_id = auth()->id();
        });
    }
}
