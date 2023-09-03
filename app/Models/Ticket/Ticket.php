<?php

namespace App\Models\Ticket;

use App\Models\Event;
use App\Models\User;
use App\Models\WaitingMember;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'price',
        'quantity_available',
        'quantity_sold',
        'quantity_attended',
        'start_sale_date',
        'end_sale_date',
        'is_hidden',
        'on_sale',
        'user_id',
        'event_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function ticketCodes(): HasMany
    {
        return $this->hasMany(TicketCode::class);
    }
    public function waitingMembers(): HasMany
    {
        return $this->hasMany(WaitingMember::class);
    }


}
