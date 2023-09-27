<?php

namespace Savannabits\Saas\Helpers;

use Savannabits\Saas\Settings\WebserviceSettings;

class Ws
{
    public static function settings(): WebserviceSettings
    {
        return new WebserviceSettings();
    }

    /**
     * @deprecated use Ws::settings() instead.
     * @return WebserviceSettings
     */
    public static function webservice_settings(): WebserviceSettings
    {
        return static::settings();
    }
}
