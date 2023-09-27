<?php

namespace Savannabits\Saas\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Savannabits\Saas\Concerns\Policy\InheritsStandardPolicy;
use Savannabits\Saas\Filament\Resources\RoleResource;

class RolePolicy
{
    use HandlesAuthorization;
    use InheritsStandardPolicy;

    public function getResourceClass(): string
    {
        return RoleResource::class;
    }
}
