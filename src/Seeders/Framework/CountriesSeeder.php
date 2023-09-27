<?php

namespace Savannabits\Saas\Seeders\Framework;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Countries\Package\Countries;
use Savannabits\Saas\Helpers\Access;
use Savannabits\Saas\Models\Country;

class CountriesSeeder extends Seeder
{
    public function run(): void
    {
        $this->command?->comment('Seeding Countries');
        Auth::login(Access::system_user());
        $countries = new Countries();
        $countries->all()->each(function ($country) {
            Country::firstOrCreate(['code' => $country->get('cca3')],[
                'cca2'          => $country->get('cca2'),
                'cca3'          => $country->get('cca3'),
                'name'          => $country->get('name.common'),
                'capital'       => $country->get('capital.0'),
                'flag_emoji'         => $country->get('flag.emoji'),
                'flag_svg_path'   => $country->get('flag.svg_path'),
                'currency_code' => $country->get('currencies.0'),
            ]);
        });
        Auth::logout();
    }
}
