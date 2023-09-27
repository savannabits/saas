<?php

namespace Savannabits\Saas;

use Filament\Contracts\Plugin;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\SpatieLaravelTranslatablePlugin;
use Savannabits\Saas\Filament\Resources\RoleResource;
use Savannabits\Saas\Filament\Resources\UserResource;

class AccessPlugin implements Plugin
{
    private bool $useLdap = false;

    public function getId(): string
    {
        return 'savannabits-access';
    }

    public static function getNavGroupLabel(): string
    {
        return 'Access Control';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->navigationGroups([
                NavigationGroup::make(static::getNavGroupLabel())->collapsible()->collapsed(),
            ])
            ->resources([
                UserResource::class,
                RoleResource::class,
            ])->plugin(SpatieLaravelTranslatablePlugin::make()->defaultLocales(['en']));
        if ($this->isUseLdap()) {
            $panel->ldap();
        } else {
            $panel->login();
        }
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function useLdap(bool $useLdap = true): AccessPlugin
    {
        $this->useLdap = $useLdap;

        return $this;
    }

    public function isUseLdap(): bool
    {
        return $this->useLdap;
    }
}
