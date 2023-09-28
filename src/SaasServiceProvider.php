<?php

namespace Savannabits\Saas;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Savannabits\Saas\Commands\ArmorGenerateCommand;
use Savannabits\Saas\Commands\ArmorSuperAdminCommand;
use Savannabits\Saas\Livewire\SwitchTeam;
use Savannabits\Saas\Providers\SavannabitsServiceProvider;
use Savannabits\Saas\Seeders\AccessDatabaseSeeder;
use Savannabits\Saas\Seeders\FrameworkSeeder;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Savannabits\Saas\Commands\SaasCommand;
use Savannabits\Saas\Testing\TestsSaas;

class SaasServiceProvider extends PackageServiceProvider
{
    public static string $name = 'savannabits-saas';

    public static string $viewNamespace = 'savannabits-saas';

    public function configurePackage(Package $package): void
    {
        $this->app->register(SavannabitsServiceProvider::class);
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasRoutes($this->getRoutes())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('savannabits/saas')
                    ->endWith(fn(InstallCommand $command) => $this->extendInstallation($command));
            });

        $package->hasConfigFile([
            'armor',
            'saas',
            'vanadi',
            'ldap',
            'permission',
            'vanadi-auth',
            'vanadi-ldap',
            'vanadi-permission',
        ]);
        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageRegistered(): void
    {
    }

    public function packageBooted(): void
    {
        Livewire::component(static::$viewNamespace.'::switch-team',SwitchTeam::class);
        $this->registerConfigs();
        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        // Icon Registration
        FilamentIcon::register($this->getIcons());

        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/saas/{$file->getFilename()}"),
                ], 'saas-stubs');
            }
        }

        // Testing
        Testable::mixin(new TestsSaas());
    }

    protected function getAssetPackageName(): ?string
    {
        return 'savannabits/saas';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            // AlpineComponent::make('saas', __DIR__ . '/../resources/dist/components/saas.js'),
//            Css::make('saas-styles', __DIR__ . '/../resources/dist/saas.css'),
//            Js::make('saas-scripts', __DIR__ . '/../resources/dist/saas.js'),
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            SaasCommand::class,
            ArmorSuperAdminCommand::class,
            ArmorGenerateCommand::class,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [
            'web'
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [
            'create_websockets_statistics_entries_table',
            'create_settings_table',
            'create_media_table',
            'add_ldap_columns_to_users_table',
            'modify_fields_in_users_table',
            'create_permission_tables',
            'create_code_factories_table',
            'create_teams_table',
            'create_document_cancellations_table',
            'add_framework_fields_to_users_table',
            'add_webservice_settings',
            'add_framework_settings',
            'add_webservice_fields_to_users_table',
            'create_countries_table',
            'create_currencies_table',
            'add_framework_settings',
            'create_team_user_pivot_table',
        ];
    }
    protected function registerConfigs(): void
    {
        $initialPrividers = config('auth.providers');
        $providers = array_merge($initialPrividers, \Config::get('vanadi-auth.providers'));
        \Config::set('auth.providers', $providers);
        // Override spatie laravel permission
        \Config::set('permission', \Config::get('vanadi-permission'));

        // Override LDAP settings
        \Config::set('ldap', \Config::get('vanadi-ldap'));
    }
    private function extendInstallation(InstallCommand $command): void
    {
        if ($command->confirm('Seed Initial Setup Data? (recommended)', true)) {
            $command->call('db:seed', ['--class' => AccessDatabaseSeeder::class]);
            $command->call('db:seed', ['--class' => FrameworkSeeder::class]);
        }

        if ($command->confirm('Would you like to generate All Permissions now?', true)) {
            $command->call('armor:generate', ['--all' => true]);
            $command->comment('Attempting to clear the permission cache: If this fails run it manually as the www-data user.');
            $command->call('permission:cache-reset');
        }
        $command->alert('DONE!');
    }
}
