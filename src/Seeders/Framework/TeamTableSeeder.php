<?php

namespace Savannabits\Saas\Seeders\Framework;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Savannabits\Saas\Helpers\Access;
use Savannabits\Saas\Models\Team;

class TeamTableSeeder extends Seeder
{
    public function run(): void
    {
        Team::firstOrCreate(['code' =>'DEFAULT'],[
            'name' => 'DEFAULT TEAM'
        ]);
    }
}
