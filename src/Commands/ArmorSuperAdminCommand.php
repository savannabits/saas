<?php

namespace Savannabits\Saas\Commands;

use Filament\Facades\Filament;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Auth\SessionGuard;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Savannabits\Saas\Commands\Concerns\CanValidateInput;
use Savannabits\Saas\Facades\Armor;
use Savannabits\Saas\Models\Role;
use Savannabits\Saas\Support\Utils;

class ArmorSuperAdminCommand extends Command
{
    use CanValidateInput;

    public $signature = 'armor:super-admin
        {--user= : ID of user to be made super admin.}
        {--panel= : Panel ID to get the configuration from.}
        ';

    public $description = 'Creates Filament Super Admin';

    protected $superAdmin;

    public function handle(): int
    {
        if ($this->option('panel')) {
            Filament::setCurrentPanel(Filament::getPanel($this->option('panel')));
        }

        /** @var SessionGuard $auth */
        $auth = Filament::getCurrentPanel()?->auth();

        /** @var EloquentUserProvider $userProvider */
        $userProvider = $auth->getProvider();

        if (Utils::getRoleModel()::whereName(Utils::getSuperAdminName())->doesntExist()) {
            Armor::createRole();
        }

        if (Utils::isFilamentUserRoleEnabled() && Utils::getRoleModel()::whereName(Utils::getFilamentUserRoleName())->doesntExist()) {
            Armor::createRole(isSuperAdmin: false);
        }

        if ($this->option('user')) {
            $this->superAdmin = $userProvider->getModel()::findOrFail($this->option('user'));
        } elseif ($userProvider->getModel()::count() === 1) {
            $this->superAdmin = $userProvider->getModel()::first();
        } elseif ($userProvider->getModel()::count() > 1) {
            $this->table(
                ['ID', 'Username', 'Name', 'Email', 'Roles'],
                $userProvider->getModel()::with('roles')->get()->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'username' => $user->username,
                        'name' => $user->name,
                        'email' => $user->email,
                        'roles' => implode(',', $user->roles->pluck('name')->toArray()),
                    ];
                })
            );

            $superAdminId = $this->ask('Please provide the `UserID` to be set as `super_admin`');

            $this->superAdmin = $userProvider->getModel()::findOrFail($superAdminId);
        } else {
            $this->superAdmin = $userProvider->getModel()::create([
                'name' => $this->validateInput(fn () => $this->ask('Name'), 'name', ['required']),
                'email' => $email = $this->validateInput(fn () => $this->ask('Email address'), 'email', ['required', 'email', 'unique:' . $userProvider->getModel()]),
                'username' => $this->validateInput(fn () => $this->ask('Username', Str::of($email)->before('@')->toString()), 'username', ['required', 'unique:' . $userProvider->getModel()]),
                'password' => Hash::make($this->validateInput(fn () => $this->secret('Password'), 'password', ['required', 'min:8'])),
            ]);
        }
        $superAdmin = Role::whereName(Utils::getSuperAdminName())->firstOrFail();
        $this->superAdmin->assignRole($superAdmin);

        if (Utils::isFilamentUserRoleEnabled()) {
            $this->superAdmin->assignRole(Utils::getFilamentUserRoleName());
        }

        $loginUrl = Filament::getCurrentPanel()?->getLoginUrl();

        $this->info("Success! {$this->superAdmin->email} may now log in at {$loginUrl}.");

        return self::SUCCESS;
    }
}
