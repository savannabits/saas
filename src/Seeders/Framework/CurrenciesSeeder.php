<?php

namespace Savannabits\Saas\Seeders\Framework;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Countries\Package\Services\Countries;
use Savannabits\Saas\Helpers\Access;
use Savannabits\Saas\Models\Currency;
use function Savannabits\Saas\system_user;

class CurrenciesSeeder extends Seeder
{
    public function run(): void
    {
        $this->command?->comment('Seeding Currencies');
        Auth::login(system_user());
        $countries = new Countries();
        $countries->currencies()->each(function($currency) {
            Currency::firstOrCreate(['code' => $currency->get('iso.code')],[
                'name' => $currency->get('name'),
                'symbol' => $currency->get('units.major.symbol'),
            ]);
        });
        Auth::logout();
    }
}
