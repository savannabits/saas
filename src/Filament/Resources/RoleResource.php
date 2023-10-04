<?php

namespace Savannabits\Saas\Filament\Resources;

use Savannabits\Saas\AccessPlugin;

class RoleResource extends \BezhanSalleh\FilamentShield\Resources\RoleResource
{
    public static function getNavigationGroup(): ?string
    {
        return AccessPlugin::getNavGroupLabel();
    }
    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }
}
