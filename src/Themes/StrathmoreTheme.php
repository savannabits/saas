<?php

namespace Savannabits\Saas\Themes;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Assets\Theme;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentAsset;

class StrathmoreTheme implements Plugin
{
    public function getId(): string
    {
        return 'strathmore-theme';
    }

    public function register(Panel $panel): void
    {
        FilamentAsset::register([
            Theme::make($this->getId(), __DIR__ . '/../../resources/dist/strathmore-theme.css'),
        ]);
        $panel
//            ->font('DM Sans')
            ->colors([
                'primary' => '#02338D',
                'info' => '#CC9C4A',
                'gray' => Color::Gray,
                'warning' => Color::Amber,
                'danger' => Color::Rose,
                'success' => Color::Green,
            ])
            ->theme($this->getId())
            ->maxContentWidth('screen-2xl')
        ;
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
}
