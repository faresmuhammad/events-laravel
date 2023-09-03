<?php

namespace Tests\Trait;

use App\Models\Profile;
use App\Models\Role;
use App\Models\User;

trait UserRoles
{

    function user()
    {
        return User::factory()->hasAttached(Profile::factory())->create();
    }

    function adminUser()
    {
        $user = User::factory()->hasAttached(Profile::factory())->create();
        $user->roles()->sync([Role::SYSTEM_ADMIN], false);
        return $user;
    }

    function regularUser()
    {
        $user = User::factory()->hasAttached(Profile::factory())->create();
        $user->roles()->sync([Role::User], false);
        return $user;
    }

    function organizerUser()
    {
        $user = User::factory()->hasAttached(Profile::factory())->create();
        $user->roles()->sync([Role::Organizer], false);
        return $user;
    }
}
