<?php

namespace Savannabits\Saas\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Savannabits\Saas\SaasSetup
 */
class Vanadi extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Savannabits\Saas\Vanadi::class;
    }
}
