<?php

namespace Savannabits\Saas\Policies;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;
use Savannabits\Saas\Concerns\Policy\InheritsStandardPolicy;
use Savannabits\Saas\Filament\Resources\CountryResource;

class CountryPolicy
{
    use HandlesAuthorization, InheritsStandardPolicy;

    function getResourceClass(): string
    {
        return CountryResource::class;
    }
    public function update(User $user, Model $model): bool
    {
        return false;
    }
    public function delete(User $user, Model $model)
    {
        return false;
    }
    public function deleteAny(User $user): bool
    {
        return false;
    }
}
