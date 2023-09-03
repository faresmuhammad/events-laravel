<?php

namespace App\Models;

use App\Enums\ProfileTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $casts = [
        'type' => ProfileTypeEnum::class
    ];


    protected $guarded = [];

    public function organizers()
    {
        return $this->belongsToMany(User::class, 'user_profile_pivot');
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    protected static function booted()
    {
        static::created(function (Profile $profile) {
            auth()->check() ?? $profile->organizers()->sync([auth()->id()]);
        });
    }
}
