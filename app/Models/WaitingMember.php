<?php

namespace App\Models;

use App\Models\Ticket\Ticket;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WaitingMember extends Model
{
    use HasFactory;

    protected $primaryKey = 'code';

    public $timestamps = false;

    protected $guarded = [];


    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function ticket():BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
}
