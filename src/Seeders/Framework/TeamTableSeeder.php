<?php

namespace Savannabits\Saas\Seeders\Framework;

use App\Models\Team;
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
