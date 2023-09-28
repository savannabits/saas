<?php

namespace Savannabits\Saas;

use Filament\Contracts\Plugin;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\SpatieLaravelTranslatablePlugin;
use Savannabits\Saas\Filament\Resources\RoleResource;
use Savannabits\Saas\Filament\Resources\UserResource;
use Savannabits\Saas\Http\Middleware\RedirectIfInertiaMiddleware;

class AccessPlugin implements Plugin
{
    private bool $useLdap = false;
    private bool $registerResources = true;
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
            ->middleware([
                RedirectIfInertiaMiddleware::class,
            ])
            ->discoverPages(in: __DIR__ . '/../Filament/Pages', for: 'Vanadi\\Vanadi\\Filament\\Pages')
            ->navigationGroups([
                NavigationGroup::make(static::getNavGroupLabel())->collapsible()->collapsed(),
            ]);
        if ($this->isRegisterResources()) {
            $panel->resources([
                UserResource::class,
                RoleResource::class,
            ]);
        }
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

    public function registerResources(bool $registerResources = true): AccessPlugin
    {
        $this->registerResources = $registerResources;
        return $this;
    }

    public function isRegisterResources(): bool
    {
        return $this->registerResources;
    }
}
