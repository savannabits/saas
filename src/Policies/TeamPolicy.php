<?php

namespace Savannabits\Saas\Policies;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Savannabits\Saas\Concerns\Policy\InheritsStandardPolicy;
use Savannabits\Saas\Filament\Resources\TeamResource;

class TeamPolicy
{
    use HandlesAuthorization, InheritsStandardPolicy;

    function getResourceClass(): string
    {
        return TeamResource::class;
    }
}
