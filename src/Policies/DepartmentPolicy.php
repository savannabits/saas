<?php

namespace Savannabits\Saas\Policies;
use Savannabits\Saas\Concerns\Policy\InheritsStandardPolicy;
use App\Models\User;
use Savannabits\Saas\Filament\Resources\DepartmentResource;
use Illuminate\Auth\Access\HandlesAuthorization;

class DepartmentPolicy
{
    use HandlesAuthorization, InheritsStandardPolicy;

    function getResourceClass(): string
    {
        return DepartmentResource::class;
    }
}
