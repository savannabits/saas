<?php

namespace Savannabits\Saas\Helpers;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

class Access
{
    public static function system_user(): User|Authenticatable
    {
        return User::whereUsername('system-process')->firstOrFail();
    }
}
