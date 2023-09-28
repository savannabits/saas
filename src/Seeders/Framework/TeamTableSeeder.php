<?php

namespace Savannabits\Saas\Seeders\Framework;

use Savannabits\Saas\Models\Team;
use Illuminate\Database\Seeder;

class TeamTableSeeder extends Seeder
{
    public function run(): void
    {
        Team::firstOrCreate(['code' =>'DEFAULT'],[
            'name' => 'DEFAULT TEAM'
        ]);
    }
}
