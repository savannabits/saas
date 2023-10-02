<?php

namespace Savannabits\Saas;

use Savannabits\Saas\Filament\Resources\DepartmentResource;
use Savannabits\Saas\Models\Team;
use Filament\Contracts\Plugin;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Blade;
use Savannabits\Saas\Filament\Pages\ManageWebserviceSettings;
use Savannabits\Saas\Filament\Resources\CountryResource;
use Savannabits\Saas\Filament\Resources\CurrencyResource;
use Savannabits\Saas\Filament\Resources\TeamResource;
use Savannabits\Saas\Helpers\Framework;
use Savannabits\Saas\Http\Middleware\RedirectIfInertiaMiddleware;

class SaasPlugin implements Plugin
{
    private bool $registerResources = true;
    private bool $registerPages = true;
    public function getId(): string
    {
        return 'savannabits-saas';
    }

    public static function getNavigationGroupLabel(): string
    {
        return 'System Setup';
    }

    public function register(Panel $panel): void
    {
        app()->singleton('savannabits-framework', Framework::class);
        app()->singleton($this->getId(), Saas::class);
        $panel->navigationGroups([
            NavigationGroup::make(static::getNavigationGroupLabel()),
            NavigationGroup::make('Settings')->collapsible()->collapsed(),
        ])
            ->middleware([
                RedirectIfInertiaMiddleware::class,
            ])
            ->discoverPages(in: __DIR__ . '/../Filament/Pages', for: 'Savannabits\\Saas\\Filament\\Pages')
//            ->tenant(Team::class)
//            ->tenantRegistration(RegisterTeam::class)
        ;

        if ($this->shouldRegisterPages()) {
            $panel->pages([
                ManageWebserviceSettings::class,
            ]);
        }
        if ($this->shouldRegisterResources()) {
            $panel->resources([
                TeamResource::class,
                CurrencyResource::class,
                CountryResource::class,
                DepartmentResource::class
            ]);
        }
    }

    public function boot(Panel $panel): void
    {
        FilamentView::registerRenderHook(
            'panels::user-menu.before',
            fn() => view('savannabits-saas::team-switcher')
        );
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

    public function registerResources(bool $registerResources = true): SaasPlugin
    {
        $this->registerResources = $registerResources;
        return $this;
    }

    public function shouldRegisterResources(): bool
    {
        return $this->registerResources;
    }

    public function registerPages(bool $registerPages = true): SaasPlugin
    {
        $this->registerPages = $registerPages;
        return $this;
    }

    public function shouldRegisterPages(): bool
    {
        return $this->registerPages;
    }
}
