<?php

namespace Savannabits\Saas\Concerns;

use Spatie\Permission\Traits\HasRoles;
use Savannabits\Saas\Support\Utils;

trait HasArmor
{
    use HasRoles;

    public static function bootHasArmor()
    {
        if (Utils::isFilamentUserRoleEnabled()) {
            static::created(fn ($user) => $user->assignRole(Utils::getFilamentUserRoleName()));

            static::deleting(fn ($user) => $user->removeRole(Utils::getFilamentUserRoleName()));
        }
    }

    public function canAccessFilament(): bool
    {
        return $this->hasRole(Utils::getSuperAdminName()) || $this->hasRole(Utils::getFilamentUserRoleName());
    }
}
