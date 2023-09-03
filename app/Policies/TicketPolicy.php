<?php

namespace App\Policies;

use App\Models\Ticket\Ticket;
use App\Models\Ticket\TicketCode;
use App\Models\User;

class TicketPolicy
{

    public function organizer(User $user, Ticket $ticket)
    {
        return $ticket->event->profile->organizers->contains(fn($organizer) => $organizer === $user);
    }
    public function update(User $user, Ticket $ticket): bool
    {
        return $ticket->event->profile->organizers->contains(fn($organizer) => $organizer === $user);
    }

    public function destroy(User $user, Ticket $ticket): bool
    {
        return $ticket->event->profile->organizers->contains(fn($organizer) => $organizer === $user);
    }

    public function attend(User $user, Ticket $ticket):bool
    {
        $isNotOrganizer = $ticket->event->profile->organizers->contains(fn($organizer) => $organizer !== $user);
        return $isNotOrganizer;
    }

    public function cancel(User $user,TicketCode $ticketCode)
    {
        return $ticketCode->user_id == $user->id;
    }
}
