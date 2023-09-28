<?php

namespace Savannabits\Saas\Seeders;

use App\Models\Team;
use App\Models\User;
use Hash;
use Illuminate\Database\Seeder;
use Str;
use Throwable;
class AdminUserSeeder extends Seeder
{
    const ADMIN_EMAIL ='admin@savannabits.com';
    /**
     * @throws Throwable
     */
    public function run(): void
    {
        $existing = User::whereEmail(self::ADMIN_EMAIL)->exists();
        if (!$existing) {
            $user = new User([
                'name' => 'System Admin',
                'email' => self::ADMIN_EMAIL,
                'username' => 'sysadmin',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'team_id' => Team::whereCode('DEFAULT')->firstOrFail()->id,
            ]);
            $user->saveOrFail();
            $user->submit();

            \Artisan::call('armor:super-admin',['--user' => $user->id, '--no-interaction' => true]);
//            \Artisan::call('armor:generate',['--all' => true,'--minimal' => true,'--option' => 'permissions'],$this->command->getOutput());
        }
    }
}
