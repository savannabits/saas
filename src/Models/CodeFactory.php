<?php

namespace Savannabits\Saas\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class CodeFactory extends Model
{
    use HasUuids;

    protected $guarded = ['id', 'series'];

    public static function generate(string $prefix, ?int $padLength = 2, ?string $padWith = '0', bool $omitPrefix = false): string
    {
        $prefix = \Str::of($prefix)->upper()->toString();
        $lastCode = static::query()->wherePrefix($prefix)
            ->latest()
            ->orderByDesc('series')
            ->first()?->series ?? 0;
        $newCode = static::query()->forceCreate(['prefix' => $prefix, 'series' => $lastCode + 1]);
        $code = \Str::of($newCode->series)->padLeft($padLength ?? 2, $padWith ?? '0');
        if (! $omitPrefix) {
            $code = $code->prepend($prefix);
        }

        return $code->toString();
    }
}
