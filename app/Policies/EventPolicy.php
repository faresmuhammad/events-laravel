<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EventPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Event $event): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Event $event): bool
    {

        return $user->roles->contains(fn($role) => in_array($role, [Role::SYSTEM_ADMIN, Role::Organizer]))
            || $event->user_id == $user->id;

    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Event $event): bool
    {
        return $user->roles->contains(fn($role) => $role === Role::SYSTEM_ADMIN)
            || $event->user_id == $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Event $event)
    {
        return $user->roles->contains(fn($role) => $role === Role::SYSTEM_ADMIN);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Event $event)
    {
        return $user->roles->contains(fn($role) => $role === Role::SYSTEM_ADMIN);
    }
}
