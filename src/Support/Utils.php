<?php

namespace Savannabits\Saas\Support;

use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Facades\Filament;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class Utils
{
    public static function getFilamentAuthGuard(): string
    {
        return Filament::getCurrentPanel()?->getAuthGuard() ?? '';
    }

    public static function isResourcePublished(): bool
    {
        $roleResourcePath = app_path(Str::of('Filament\\Admin\\Resources\\RoleResource.php')->replace('\\', '/')->toString()
        );

        $filesystem = new Filesystem();

        return (bool) $filesystem->exists($roleResourcePath);
    }

    public static function getResourceSlug(): string
    {
        return (string) config('armor.resource.slug');
    }

    public static function isResourceNavigationRegistered(): bool
    {
        return config('armor.resource.should_register_navigation', true);
    }

    public static function getResourceNavigationSort(): ?int
    {
        return config('armor.resource.navigation_sort');
    }

    public static function isResourceNavigationBadgeEnabled(): bool
    {
        return config('armor.resource.navigation_badge', true);
    }

    public static function isResourceNavigationGroupEnabled(): bool
    {
        return config('armor.resource.navigation_group', true);
    }

    public static function isResourceGloballySearchable(): bool
    {
        return config('armor.resource.is_globally_searchable', false);
    }

    public static function getAuthProviderFQCN()
    {
        return config('armor.auth_provider_model.fqcn');
    }

    public static function isAuthProviderConfigured(): bool
    {
        return in_array('Savannabits\Saas\\Concerns\\HasArmor', class_uses(static::getAuthProviderFQCN()))
        || in_array("Spatie\Permission\Traits\HasRoles", class_uses(static::getAuthProviderFQCN()));
    }

    public static function isSuperAdminEnabled(): bool
    {
        return (bool) config('armor.super_admin.enabled', true);
    }

    public static function getSuperAdminName(): string
    {
        return (string) config('armor.super_admin.name');
    }

    public static function isSuperAdminDefinedViaGate(): bool
    {
        return (bool) static::isSuperAdminEnabled() && config('armor.super_admin.define_via_gate', false);
    }

    public static function getSuperAdminGateInterceptionStatus(): string
    {
        return (string) config('armor.super_admin.intercept_gate');
    }

    public static function isFilamentUserRoleEnabled(): bool
    {
        return (bool) config('armor.standard_user.enabled', true);
    }

    public static function getFilamentUserRoleName(): string
    {
        return (string) config('armor.standard_user.name');
    }

    public static function getGeneralResourcePermissionPrefixes(): array
    {
        return config('armor.permission_prefixes.resource');
    }

    public static function getPagePermissionPrefix(): string
    {
        return (string) config('armor.permission_prefixes.page');
    }

    public static function getWidgetPermissionPrefix(): string
    {
        return (string) config('armor.permission_prefixes.widget');
    }

    public static function isResourceEntityEnabled(): bool
    {
        return (bool) config('armor.entities.resources', true);
    }

    public static function isPageEntityEnabled(): bool
    {
        return (bool) config('armor.entities.pages', true);
    }

    /**
     * Widget Entity Status
     */
    public static function isWidgetEntityEnabled(): bool
    {
        return (bool) config('armor.entities.widgets', true);
    }

    public static function isCustomPermissionEntityEnabled(): bool
    {
        return (bool) config('armor.entities.custom_permissions', false);
    }

    public static function getGeneratorOption(): string
    {
        return (string) config('armor.generator.option', 'policies_and_permissions');
    }

    public static function isGeneralExcludeEnabled(): bool
    {
        return (bool) config('armor.exclude.enabled', true);
    }

    public static function enableGeneralExclude(): void
    {
        config(['armor.exclude.enabled' => true]);
    }

    public static function disableGeneralExclude(): void
    {
        config(['armor.exclude.enabled' => false]);
    }

    public static function getExcludedResouces(): array
    {
        return config('armor.exclude.resources');
    }

    public static function getExcludedPages(): array
    {
        return config('armor.exclude.pages');
    }

    public static function getExcludedWidgets(): array
    {
        return config('armor.exclude.widgets');
    }

    public static function isRolePolicyRegistered(): bool
    {
        return (bool) config('armor.register_role_policy', true);
    }

    public static function doesResourceHaveCustomPermissions(string $resourceClass): bool
    {
        return in_array(HasShieldPermissions::class, class_implements($resourceClass));
    }

    public static function showModelPath(string $resourceFQCN): string
    {
        return config('armor.resource.show_model_path', true)
            ? get_class(new ($resourceFQCN::getModel())())
            : '';
    }

    public static function getResourcePermissionPrefixes(string $resourceFQCN): array
    {
        return static::doesResourceHaveCustomPermissions($resourceFQCN)
            ? $resourceFQCN::getPermissionPrefixes()
            : static::getGeneralResourcePermissionPrefixes();
    }

    public static function getRoleModel(): string
    {
        return config('permission.models.role', 'Spatie\\Permission\\Models\\Role');
    }

    public static function getPermissionModel(): string
    {
        return config('permission.models.permission', 'Spatie\\Permission\\Models\\Permission');
    }
}
