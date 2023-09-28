<?php

namespace Savannabits\Saas\Seeders\Framework;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PragmaRX\Countries\Package\Countries;
use Savannabits\Saas\Helpers\Access;
use Savannabits\Saas\Models\Country;
use function Savannabits\Saas\default_team;

class CountriesSeeder extends Seeder
{
    public function run(): void
    {
        $this->command?->comment('Seeding Countries');
        Auth::login(Access::system_user());
        DB::transaction(function () {
            $countries = new Countries();
            $countries->all()->each(function ($country) {
                $code = $country->get('cca3');
                $this->command->comment("Seeding $code");
                Country::query()->firstOrCreate(['code' => $code, 'team_id'=> default_team()->id] , [
                    'cca2'          => $country->get('cca2'),
                    'cca3'          => $country->get('cca3'),
                    'name'          => $country->get('name.common'),
                    'capital'       => $country->get('capital.0'),
                    'flag_emoji'         => $country->get('flag.emoji'),
                    'flag_svg_path'   => $country->get('flag.svg_path'),
                    'currency_code' => $country->get('currencies.0'),
                ]);
            });
        });
        Auth::logout();
    }
}
