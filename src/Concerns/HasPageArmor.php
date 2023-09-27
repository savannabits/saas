<?php

namespace Savannabits\Saas\Concerns;

use Filament\Facades\Filament;
use Illuminate\Support\Str;
use Savannabits\Saas\Support\Utils;

trait HasPageArmor
{
    public function booted(): void
    {
        $this->beforeBooted();

        if (! static::canView()) {
            $this->notify('warning', __('filament-shield::filament-shield.forbidden'));

            $this->beforeArmorRedirects();

            redirect($this->getArmorRedirectPath());

            return;
        }

        if (method_exists(parent::class, 'booted')) {
            parent::booted();
        }

        $this->afterBooted();
    }

    protected function beforeBooted(): void
    {
    }

    protected function afterBooted(): void
    {
    }

    protected function beforeArmorRedirects(): void
    {
    }

    protected function getArmorRedirectPath(): string
    {
        return Filament::getDefaultPanel()?->getPath();
    }

    public static function canView(): bool
    {
        return Filament::auth()->user()->can(static::getPermissionName()) || Filament::auth()->user()->hasRole(Utils::getSuperAdminName());
    }

    protected static function getPermissionName(): string
    {
        $prepend = Str::of(Utils::getPagePermissionPrefix())->append('_');

        return Str::of(class_basename(static::class))
            ->prepend($prepend);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canView() && static::$shouldRegisterNavigation;
    }
}
