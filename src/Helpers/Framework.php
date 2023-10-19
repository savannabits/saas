<?php

namespace Savannabits\Saas\Helpers;

use Filament\Support\Colors\Color;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use LaravelReady\ReadableNumbers\Facades\ReadableNumbers;
use ReflectionClass;
use Savannabits\Saas\AccessPlugin;
use Savannabits\Saas\Themes\StrathmoreTheme;

class Framework
{
    public static function url(string $path, ?array $parameters = []): \Illuminate\Foundation\Application|string|UrlGenerator|Application
    {
        return url($path,$parameters,secure: config('vanadi.app_scheme','https') ==='https');
    }
    public static function get_models($scanPath, string $namespace = 'App\\'): Collection
    {
        $models = collect(File::allFiles($scanPath))
            ->map(function ($item) use ($namespace) {
                $path = $item->getRelativePathName();
                $class = sprintf(
                    '\%s%s',
                    $namespace,
                    strtr(substr($path, 0, strrpos($path, '.')), '/', '\\')
                );

                return $class;
            })
            ->filter(function ($class) {
                $valid = false;

                if (class_exists($class)) {
                    $reflection = new ReflectionClass($class);
                    $valid = $reflection->isSubclassOf(Model::class) &&
                        ! $reflection->isAbstract();
                }

                return $valid;
            });

        return $models->values();
    }

    public static function model_has_doc_status(Model $model): bool
    {
        return isset($model->doc_status);
    }

    public static function human_readable(float | int $number, int $decimals = 2, string $locale = null): string
    {
        if (! $locale) {
            $locale = app()->getLocale();
        }

        return ReadableNumbers::make($number, $decimals, $locale);
    }

    public function calculate(string $mathExpression, array $variables = []): mixed
    {
        $expression = $this->substitute($mathExpression, $variables);
        $math = "return $expression;";

        return eval($math);
    }

    public function substitute(string $expression, array $substitutions = [], string $substitutionIdentifier = ':'): string
    {
        $keys = collect($substitutions)->keys()->map(fn ($key) => "{$substitutionIdentifier}{$key}");
        $values = collect($substitutions)->values();

        return Str::of($expression)->replace($keys->toArray(), $values->toArray())->toString();
    }

    public function getByCode(Model | string $model, string $code)
    {
        if (! Schema::hasColumn($model::query()->getModel()->getTable(), 'code')) {
            return null;
        }
        if (is_array($code)) {
            return $model::query()->whereIn('code', $code)->get();
        }
        return $model::query()->where('code', '=', trim($code))->first();
    }

    public function strToBool(mixed $string): bool
    {
        return in_array(strtolower(
            (string) $string),
            ['y', 't', '1', 'yes', 'true', 'on','active','activated', 'enabled']
        );
    }

    public function tailwind_palette(string|array $color): array
    {
        if (is_array($color)) return $color; // Already a palette
        return Str::of($color)->contains('#') ?Color::hex($color) : Color::rgb($color);
    }

    public function rgba_primary($level=500, $alpha=1.0): string
    {
        $primary = $this->tailwind_palette(StrathmoreTheme::PRIMARY_COLOR)[$level];
        return "rgba($primary,$alpha)";
    }
    public function rgba_info($level=500, $alpha=1.0): string
    {
        $color = $this->tailwind_palette(StrathmoreTheme::INFO_COLOR)[$level];
        return "rgba($color,$alpha)";
    }
    public function rgba_danger($level=500, $alpha=1.0): string
    {
        $color = $this->tailwind_palette(StrathmoreTheme::DANGER_COLOR)[$level];
        return "rgba($color,$alpha)";
    }
    public function rgba_success($level=500, $alpha=1.0): string
    {
        $color = $this->tailwind_palette(StrathmoreTheme::SUCCESS_COLOR)[$level];
        return "rgba($color,$alpha)";
    }

}
