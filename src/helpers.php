<?php
namespace Savannabits\Saas;

use App\Model\User;
use App\Models\Team;
use Savannabits\Saas\Helpers\Access;
use Savannabits\Saas\Models\Currency;
use Savannabits\Saas\Settings\WebserviceSettings;

if (!function_exists('Savannabits\Saas\team')) {
    function team(string $code): ?Team
    {
        return Team::whereCode($code)->first();
    }
}
if (!function_exists('Savannabits\Saas\default_team')) {
    function default_team(): Team
    {
        return Team::whereCode('DEFAULT')->firstOrFail();
    }
}


if (!function_exists('Savannabits\Saas\currency')) {
    function currency(string $code): ?Currency
    {
        return Currency::whereCode($code)->first();
    }
}

if (!function_exists('Savannabits\Saas\system_user')) {
    function system_user(): \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
    {
        return Access::system_user();
    }
}
if (!function_exists('Savannabits\Saas\webservice_settings')) {
    function webservice_settings(): WebserviceSettings
    {
        return new WebserviceSettings();
    }
}

