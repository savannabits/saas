<?php

namespace Savannabits\Saas\Seeders\Framework;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Countries\Package\Services\Countries;
use Savannabits\Saas\Helpers\Access;
use Savannabits\Saas\Models\Currency;
use function Savannabits\Saas\default_team;
use function Savannabits\Saas\system_user;

class CurrenciesSeeder extends Seeder
{
    public function run(): void
    {
        $this->command?->comment('Seeding Currencies');
        Auth::login(system_user());
        $countries = new Countries();
        $currencies = [];
        $countries->currencies()->each(function($currency) use (&$currencies) {
            $currencies[] = [
                'code' => $currency->get('iso.code'),
                'name' => $currency->get('name'),
                'symbol' => $currency->get('units.major.symbol'),
            ];
        });
        foreach ($currencies as $currency) {
            $this->command->comment("Seeding {$currency['code']}");
            Currency::firstOrCreate(['code' => $currency['code'],'team_id'=> default_team()->id], $currency);
        }
        Auth::logout();
    }
}
