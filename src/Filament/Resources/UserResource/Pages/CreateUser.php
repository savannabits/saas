<?php

namespace Savannabits\Saas\Filament\Resources\UserResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Savannabits\Saas\Filament\Resources\UserResource;

class CreateUser extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = UserResource::class;
}
