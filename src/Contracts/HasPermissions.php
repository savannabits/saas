<?php

namespace Savannabits\Saas\Contracts;

interface HasPermissions
{
    public static function getPermissionPrefixes(): array;
}
