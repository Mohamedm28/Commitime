<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ScreenTime;


class ScreenTimePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    public function view(User $user, ScreenTime $screenTime)
    {
        return $user->id === $screenTime->user_id || $user->isParentOf($screenTime->user_id);
    }
}
