<?php

namespace Savannabits\Saas\Seeders;

use Savannabits\Saas\Models\Team;
use App\Models\User;
use Hash;
use Illuminate\Database\Seeder;
use Savannabits\Saas\Seeders\Framework\TeamTableSeeder;
use Throwable;
use function Savannabits\Saas\default_team;

class SystemUserSeeder extends Seeder
{
    /**
     * @throws Throwable
     */
    public function run(): void
    {
        // Ensure default team is seeded.
        $this->call(TeamTableSeeder::class);

        $existing = User::whereEmail('system@process.user')->exists();
        if (!$existing) {
            $user = User::create([
                'name'              => 'System Process User',
                'email'             => 'system@process.user',
                'username'          => 'system-process',
                'user_number'       => 'SPU',
                'email_verified_at' => now(),
                'user_type_code'    => 'SYSTEM',
                'team_id'           => default_team()?->getAttribute('id'),
            ]);
            $user->saveOrFail();
            $user->submit();
        }
    }
}
