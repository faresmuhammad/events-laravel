<?php

namespace App\Policies;

use App\Enums\ProfileTypeEnum;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProfilePolicy
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
    public function view(User $user, Profile $profile): bool
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
    public function update(User $user, Profile $profile): bool
    {
        //system admin can update any profile
        //authenticated user can update his own profile
        $isSystemAdmin = $user->roles->contains(fn($role) => $role === Role::SYSTEM_ADMIN);
        $isOrganizer = $profile->organizers->contains(fn($organizer) => $organizer === $user);
        return $isSystemAdmin || $isOrganizer;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Profile $profile): bool
    {
        return $user->roles->contains(fn($role) => $role === Role::SYSTEM_ADMIN);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Profile $profile)
    {
        return $user->roles->contains(fn($role) => $role === Role::SYSTEM_ADMIN);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Profile $profile)
    {
        return $user->roles->contains(fn($role) => $role === Role::SYSTEM_ADMIN);
    }
}
