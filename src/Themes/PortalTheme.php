<?php

namespace Savannabits\Saas\Themes;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Assets\Theme;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentAsset;

class PortalTheme implements Plugin
{
    const PRIMARY_COLOR = '#02338D';
    const INFO_COLOR = '#CC9C4A';
    const GRAY_COLOR = Color::Gray;
    const DANGER_COLOR = Color::Rose;
    const SUCCESS_COLOR = Color::Green;
    const WARNING_COLOR = Color::Amber;
    public function getId(): string
    {
        return 'portal-theme';
    }

    public function register(Panel $panel): void
    {
        FilamentAsset::register([
            Theme::make($this->getId(), __DIR__ . '/../../resources/dist/portal-theme.css'),
        ]);
        $panel
//            ->font('DM Sans')
            ->colors([
                'primary' => static::PRIMARY_COLOR,
                'info' => static::INFO_COLOR,
                'gray' => static::GRAY_COLOR,
                'warning' => static::WARNING_COLOR,
                'danger' => static::DANGER_COLOR,
                'success' => static::SUCCESS_COLOR,
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
