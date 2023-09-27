<?php

namespace Savannabits\Saas\Helpers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use LaravelReady\ReadableNumbers\Facades\ReadableNumbers;
use ReflectionClass;

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

    public function substitute(string $expression, array $substitutions = []): string
    {
        $keys = collect($substitutions)->keys()->map(fn ($key) => ":$key");
        $values = collect($substitutions)->values();

        return Str::of($expression)->replace($keys->toArray(), $values->toArray())->toString();
    }

    public function getByCode(Model | string $model, string $code)
    {
        if (! Schema::hasColumn($model::query()->getModel()->getTable(), 'code')) {
            return null;
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
}
