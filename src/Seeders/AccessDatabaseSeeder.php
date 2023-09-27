<?php

namespace Savannabits\Saas\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Savannabits\Saas\Helpers\Access;

class AccessDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $this->call(SystemUserSeeder::class);
        \Auth::login(Access::system_user());
        $this->call(AdminUserSeeder::class);
        \Auth::logout();
        Model::reguard();
    }
}
