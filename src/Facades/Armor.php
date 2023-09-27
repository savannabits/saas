<?php

namespace Savannabits\Saas\Facades;

use Illuminate\Support\Facades\Facade;

class Armor extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Savannabits\Saas\Support\Armor::class;
    }
}
