<?php

namespace Savannabits\Saas\Providers;

use Filament\Panel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;
use Savannabits\Saas\Filament\Pages\Login;
use Savannabits\Saas\Macros\FrameworkColumns;
use Savannabits\Saas\Macros\UuidNestedSet;

class SavannabitsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        /*Panel::macro('strathmoreBrand', function () {
            $this
                ->maxContentWidth('screen-2xl')
                ->viteTheme('resources/css/filament/portal/theme.css')
                ->colors([
                    'primary' => '#02338D',
                    'info' => '#CC9C4A'
                ])->renderHook('panels::topbar.start',
                    fn () => new HtmlString(
                        '<h2 class="text-primary-500 dark:text-info-400 font-black text-xl">'.Filament::getBrandName().'</h2>'
                    )
                );
            return $this;
        });*/
        Panel::macro('ldap', function () {
            //            config()->set('auth.guards.web.provider', 'ldap');
            $this
                ->authGuard('web')
                ->login(Login::class);

            return $this;
        });
        Blueprint::macro('statusColumns', function () {
            FrameworkColumns::statusColumns($this);
        });

        Blueprint::macro('dropStatusColumns', function () {
            FrameworkColumns::dropStatusColumns($this);
        });

        Blueprint::macro('auditColumns', function () {
            FrameworkColumns::auditColumns($this);
        });
        Blueprint::macro('dropAuditColumns', function () {
            FrameworkColumns::dropAuditColumns($this);
        });

        Blueprint::macro('reversalColumns', function () {
            FrameworkColumns::reversalColumns($this);
        });

        Blueprint::macro('dropReversalColumns', function () {
            FrameworkColumns::dropReversalColumns($this);
        });

        Blueprint::macro('uuidNestedSet', function () {
            UuidNestedSet::columns($this);
        });
        Blueprint::macro('dropUuidNestedSet', function () {
            UuidNestedSet::dropColumns($this);
        });

        /**
         * @deprecated Use teamColumn() instead
         */
        Blueprint::macro('companyColumn', function (?string $name = 'team_id') {
            FrameworkColumns::teamColumn($this, $name);
        });
        Blueprint::macro('teamColumn', function (?string $name = 'team_id') {
            FrameworkColumns::teamColumn($this, $name);
        });

        Blueprint::macro('teamCodeColumn', function (?string $codeColumn = 'code', ?string $teamColumn = 'team_id') {
            FrameworkColumns::teamCodeColumn($this, $codeColumn, $teamColumn);
        });

        Blueprint::macro('dropTeamColumn', function (?string $name = 'team_id') {
            FrameworkColumns::dropTeamColumn($this, $name);
        });

        /**
         * @deprecated Use dropTeamColumn() instead
         */
        Blueprint::macro('dropCompanyColumn', function (?string $name = 'team_id') {
            FrameworkColumns::dropTeamColumn($this, $name);
        });
    }

    public function boot(): void
    {
        \DB::getDoctrineConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
    }
}