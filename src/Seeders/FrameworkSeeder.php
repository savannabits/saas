<?php

namespace Savannabits\Saas\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Savannabits\Saas\Seeders\Framework\CountriesSeeder;
use Savannabits\Saas\Seeders\Framework\CurrenciesSeeder;
use Savannabits\Saas\Seeders\Framework\TeamTableSeeder;
use function Savannabits\Saas\system_user;

class FrameworkSeeder extends Seeder
{
    public function run(): void
    {
        Model::unguard();
        Auth::login(system_user());
        $this->call(CurrenciesSeeder::class);
        $this->call(CountriesSeeder::class);
        Auth::logout();
        Model::reguard();
    }
}
