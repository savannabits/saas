<?php

namespace Savannabits\Saas\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Savannabits\Saas\Concerns\Policy\InheritsStandardPolicy;
use Savannabits\Saas\Filament\Resources\UserResource;

class UserPolicy
{
    use HandlesAuthorization;
    use InheritsStandardPolicy;

    public function getResourceClass(): string
    {
        return UserResource::class;
    }
}
