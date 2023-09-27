<?php

namespace Savannabits\Saas\Panels;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\SpatieLaravelTranslatablePlugin;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Savannabits\Saas;

class AccessPanelProvider extends PanelProvider
{
    const PATH = 'auth';

    protected bool $useLdap = true;

    public function panel(Panel $panel): Panel
    {
        $panel = $panel
            ->id('vanadi-access')
            ->path(self::PATH)
            ->discoverResources(in: __DIR__ . '/../Filament/Access/Resources', for: 'Savannabits\Saas\\Filament\\Access\\Resources')
            ->discoverPages(in: __DIR__ . '/../Filament/Access/Pages', for: 'Savannabits\Saas\\Filament\\Access\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: __DIR__ . '/../Filament/Access/Widgets', for: 'Savannabits\Saas\\Filament\\Access\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugin(Saas\SaasPlugin::make())
            ->plugin(Saas\AccessPlugin::make()->useLdap())
            ->plugin(SpatieLaravelTranslatablePlugin::make()->defaultLocales(['en']));
        if ($this->useLdap) {
            $panel->ldap();
        } else {
            $panel->login();
        }

        return $panel;
    }
}
