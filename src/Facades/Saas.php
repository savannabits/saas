<?php

namespace Savannabits\Saas\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Savannabits\Saas\Saas
 */
class Saas extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Savannabits\Saas\Saas::class;
    }
}
