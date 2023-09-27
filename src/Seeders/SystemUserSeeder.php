<?php

namespace Savannabits\Saas\Seeders;

use Savannabits\Saas\Contracts\DocStatus;
use App\Models\User;
use Hash;
use Illuminate\Database\Seeder;
use Savannabits\Saas\Models\Team;
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

        dd(\Savannabits\Saas\team('DEFAULT'));
        $existing = User::whereEmail('system@process.user')->exists();
        if (!$existing) {
            $user = User::create([
                'name'              => 'System Process User',
                'email'             => 'system@process.user',
                'username'          => 'system-process',
                'email_verified_at' => now(),
                'team_id'           => default_team()->getAttribute('id'),
            ]);
            $user->saveOrFail();
            $user->submit();
        }
    }
}
